__author__ = 'elpaso@itopen.it'
__date__ = '2022-10-27'
__license__ = "GPL version 3"
__copyright__ = 'Copyright 2022, Gis3w'

# File adapted by @rldhont, 3Liz

from qgis.core import QgsMapLayerStyle, QgsProject
from qgis.server import QgsServerFilter, QgsServerInterface

from lizmap_server.core import find_vector_layer
from lizmap_server.logger import Logger, exception_handler


class LegendOnOffFilter(QgsServerFilter):
    """Legend ON/OFF filter

    LEGEND_ON=<layer_id>:<rule_key>,<rule_key>;<layer_id>:<rule_key>,<rule_key>
    LEGEND_OFF=<layer_id>:<rule_key>,<rule_key>;<layer_id>:<rule_key>,<rule_key>

    """

    def __init__(self, server_interface: QgsServerInterface):
        super().__init__(server_interface)
        self.style_map = None
        self.renderers_config = None

    def _setup_legend(self, qs, onoff):

        if not qs or ':' not in qs:
            return

        logger = Logger()
        # noinspection PyArgumentList
        project: QgsProject = QgsProject.instance()

        for legend_layer in qs.split(';'):
            layer_name, key_list = legend_layer.split(':')
            if layer_name == '' or key_list == '':
                continue

            keys = key_list.split(',')
            if len(keys) == 0:
                continue

            layer = find_vector_layer(layer_name, project)
            if not layer:
                logger.info("Skipping the layer '{}' because it's not a vector layer".format(layer_name))
                continue

            try:
                if layer_name not in self.renderers_config:
                    sm = layer.styleManager()
                    current_style = sm.currentStyle()
                    try:
                        style_name = self.style_map[layer_name]
                    except KeyError:
                        style_name = current_style
                    xml = sm.style(style_name).xmlData()
                    sm.setCurrentStyle(style_name)
                    self.renderers_config[layer_name] = {
                        'current_style': current_style,
                        'xml': xml,
                        'style_name': style_name,
                    }

                for key in key_list.split(','):
                    layer.renderer().checkLegendSymbolItem(key, onoff)

            except Exception as ex:
                logger.warning(
                    'Error setting legend {} for layer "{}" when configuring OWS call: {}'.format(
                        'ON' if onoff else 'OFF', layer_name, ex))
                continue

    @exception_handler
    def requestReady(self):

        self.renderers_config = {}

        handler = self.serverInterface().requestHandler()
        logger = Logger()
        if not handler:
            logger.critical('LegendOnOffFilter plugin cannot be run in multithreading mode, skipping.')
            return

        params = handler.parameterMap()

        if 'LEGEND_ON' not in params and 'LEGEND_OFF' not in params:
            return

        styles = params['STYLES'].split(',') if 'STYLES' in params and params['STYLES'] else []

        if len(styles) == 0:
            styles = [params['STYLE']] if 'STYLE' in params and params['STYLE'] else []

        layers = params['LAYERS'].split(',') if 'LAYERS' in params and params['LAYERS'] else []

        if len(layers) == 0:
            layers = [params['LAYER']] if 'LAYER' in params and params['LAYER'] else []

        # noinspection PyBroadException
        try:
            self.style_map = dict(zip(layers, styles))
        except Exception:
            self.style_map = {}

        if 'LEGEND_ON' in params:
            self._setup_legend(params['LEGEND_ON'], True)
        if 'LEGEND_OFF' in params:
            self._setup_legend(params['LEGEND_OFF'], False)

    @exception_handler
    def responseComplete(self):
        """Restore legend customized renderers"""

        handler = self.serverInterface().requestHandler()
        logger = Logger()
        if not handler:
            logger.critical(
                'LegendOnOffFilter plugin cannot be run in multithreading mode, skipping.')
            return

        if len(self.renderers_config) == 0:
            return

        # noinspection PyArgumentList
        project: QgsProject = QgsProject.instance()

        for layer_name, renderer_config in self.renderers_config.items():
            layer = find_vector_layer(layer_name, project)
            if not layer:
                logger.info("Skipping the layer '{}' because it's not a vector layer".format(layer_name))
                continue
            try:
                config = self.renderers_config[layer_name]

                sm = layer.styleManager()
                sm.renameStyle(config['style_name'], 'dirty_to_remove')
                sm.addStyle(config['style_name'], QgsMapLayerStyle(config['xml']))
                sm.setCurrentStyle(config['current_style'])
                sm.removeStyle('dirty_to_remove')

            except Exception as ex:
                logger.warning(
                    'Error restoring renderer after legend ON/OFF for layer "{}" when configuring OWS call: {}'.format(
                        layer_name, ex))
                continue
