__author__ = 'elpaso@itopen.it'
__date__ = '2022-10-27'
__license__ = "GPL version 3"
__copyright__ = 'Copyright 2022, Gis3w'

# File adapted by @rldhont, 3Liz

import json
import re

from qgis.core import Qgis, QgsDataSourceUri, QgsProject
from qgis.server import QgsServerFilter

from lizmap_server.core import find_vector_layer
from lizmap_server.logger import Logger, exception_handler
from lizmap_server.tools import to_bool


class GetLegendGraphicFilter(QgsServerFilter):
    """add ruleKey to GetLegendGraphic for categorized and rule-based
    only works for single LAYER and STYLE(S) and json format.
    """

    @exception_handler
    def responseComplete(self):

        handler = self.serverInterface().requestHandler()
        logger = Logger()
        if not handler:
            logger.critical(
                'GetLegendGraphicFilter plugin cannot be run in multithreading mode, skipping.')
            return

        params = handler.parameterMap()

        if params.get('SERVICE', '').upper() != 'WMS':
            return

        if params.get('REQUEST', '').upper() not in ('GETLEGENDGRAPHIC', 'GETLEGENDGRAPHICS'):
            return

        if params.get('FORMAT', '').upper() != 'APPLICATION/JSON':
            return

        # Only support request for simple layer
        layer_name = params.get('LAYER', '')
        if layer_name == '':
            return
        if ',' in layer_name:
            return

        # noinspection PyArgumentList
        project: QgsProject = QgsProject.instance()

        style = params.get('STYLES', '')

        if not style:
            style = params.get('STYLE', '')

        show_feature_count = to_bool(params.get('SHOWFEATURECOUNT'), default_value=False)

        current_style = ''
        layer = find_vector_layer(layer_name, project)
        if not layer:
            logger.info("Skipping the layer '{}' because it's not a vector layer".format(layer_name))
            return

        try:
            current_style = layer.styleManager().currentStyle()

            if current_style and style and style != current_style:
                layer.styleManager().setCurrentStyle(style)

            # Force count symbol features
            # It seems that in QGIS Server 3.22 countSymbolFeatures is not used for JSON
            if show_feature_count:
                counter = layer.countSymbolFeatures()
                if counter:
                    counter.waitForFinished()

            renderer = layer.renderer()

            # From QGIS source code :
            # https://github.com/qgis/QGIS/blob/71499aacf431d3ac244c9b75c3d345bdc53572fb/src/core/symbology/qgsrendererregistry.cpp#L33
            if renderer.type() in ("categorizedSymbol", "RuleRenderer", "graduatedSymbol"):
                body = handler.body()
                # noinspection PyTypeChecker
                json_data = json.loads(bytes(body))
                categories = {}
                for item in renderer.legendSymbolItems():

                    # Calculate title if show_feature_count is activated
                    # It seems that in QGIS Server 3.22 countSymbolFeatures is not used for JSON
                    title = item.label()
                    if show_feature_count:
                        estimated_count = QgsDataSourceUri(layer.dataProvider().dataSourceUri()).useEstimatedMetadata()
                        count = layer.featureCount(item.ruleKey())
                        title += ' [{}{}]'.format(
                            "≈" if estimated_count else "",
                            count if count != -1 else "N/A",
                        )

                    expression = ''
                    # TODO simplify when QGIS 3.26 will be the minimum version
                    if Qgis.QGIS_VERSION_INT >= 32600:
                        expression, result = renderer.legendKeyToExpression(item.ruleKey(), layer)
                        if not result:
                            Logger.warning(
                                f"The expression in the project {project.homePath()}, layer {layer.name()} has not "
                                f"been generated correctly, setting the expression to an empty string"
                            )
                            expression = ''

                    categories[item.label()] = {
                        'ruleKey': item.ruleKey(),
                        'checked': renderer.legendSymbolItemChecked(item.ruleKey()),
                        'parentRuleKey': item.parentRuleKey(),
                        'scaleMaxDenom': item.scaleMaxDenom(),
                        'scaleMinDenom': item.scaleMinDenom(),
                        'expression': expression,
                        'title': title,
                    }

                symbols = json_data['nodes'][0]['symbols'] if 'symbols' in json_data['nodes'][0] else json_data['nodes']

                new_symbols = []

                for idx in range(len(symbols)):
                    symbol = symbols[idx]
                    symbol_label = symbol['title']
                    if show_feature_count:
                        match_label = re.match(r"(.*) \[≈?(?:\d|N/A)\]", symbol_label)
                        if match_label:
                            symbol_label = match_label.group(1)
                        else:
                            logger.info("GetLegendGraphic JSON: symbol label does not match '(.*) \\[≈?(?:\\d|N/A)\\]' '{}'".format(symbol['title']))
                    try:
                        category = categories[symbol_label]
                        symbol['ruleKey'] = category['ruleKey']
                        symbol['checked'] = category['checked']
                        symbol['parentRuleKey'] = category['parentRuleKey']

                        # TODO remove when QGIS 3.28 will be the minimum version
                        # https://github.com/qgis/QGIS/pull/53738 3.34, 3.32.1, 3.28.10
                        if 'scaleMaxDenom' not in symbol and category['scaleMaxDenom'] > 0:
                            symbol['scaleMaxDenom'] = category['scaleMaxDenom']
                        if 'scaleMinDenom' not in symbol and category['scaleMinDenom'] > 0:
                            symbol['scaleMinDenom'] = category['scaleMinDenom']

                        symbol['expression'] = category['expression']
                        if symbol['title'] != category['title']:
                            symbol['title'] = category['title']
                    except (IndexError, KeyError):
                        pass

                    new_symbols.append(symbol)

                if 'symbols' in json_data['nodes'][0]:
                    json_data['nodes'][0]['symbols'] = new_symbols
                else:
                    json_data['nodes'] = new_symbols

                handler.clearBody()
                handler.appendBody(json.dumps(
                    json_data).encode('utf8'))
        except Exception as ex:
            logger.critical(
                'Error getting layer "{}" when setting up legend graphic for json output when configuring '
                'OWS call: {}'.format(layer_name, str(ex)))
        finally:
            if layer and style and current_style and style != current_style:
                layer.styleManager().setCurrentStyle(current_style)
