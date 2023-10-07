"""
***************************************************************************
    QGIS Server Plugin Filters: Add a new request to print a specific atlas
    feature
    ---------------------
    Date                 : December 2019
    Copyright            : (C) 2019 by René-Luc D'Hont - 3Liz
    Email                : rldhont at 3liz dot com
***************************************************************************
*                                                                         *
*   This program is free software; you can redistribute it and/or modify  *
*   it under the terms of the GNU General Public License as published by  *
*   the Free Software Foundation; either version 2 of the License, or     *
*   (at your option) any later version.                                   *
*                                                                         *
***************************************************************************
"""

import json
import traceback

from pathlib import Path
from typing import Dict

from qgis.core import QgsExpression, QgsProject
from qgis.server import QgsServerRequest, QgsServerResponse, QgsService
from qgis.utils import pluginMetadata

from .core import AtlasPrintException, parse_output_format, print_layout
from .logger import Logger
from .tools import get_lizmap_groups, get_lizmap_user_login

__copyright__ = 'Copyright 2021, 3Liz'
__license__ = 'GPL version 3'
__email__ = 'info@3liz.org'


def write_json_response(data: Dict[str, str], response: QgsServerResponse, code: int = 200) -> None:
    """ Write data as json response
    """
    response.setStatusCode(code)
    response.setHeader("Content-Type", "application/json")
    response.write(json.dumps(data))


class AtlasPrintError(Exception):

    def __init__(self, code: int, msg: str) -> None:
        super().__init__(msg)
        self.msg = msg
        self.code = code
        Logger().critical("Atlas print request error {}: {}".format(code, msg))

    def formatResponse(self, response: QgsServerResponse) -> None:
        """ Format error response
        """
        body = {'status': 'fail', 'message': self.msg}
        response.clear()
        write_json_response(body, response, self.code)


class AtlasPrintService(QgsService):

    def __init__(self, debug: bool = False) -> None:
        super().__init__()
        _ = debug
        self.logger = Logger()

    # QgsService inherited

    def name(self) -> str:
        """ Service name
        """
        return 'ATLAS'

    def version(self) -> str:
        """ Service version
        """
        return "1.0.0"

    # noinspection PyMethodMayBeStatic
    def allowMethod(self, method: QgsServerRequest.Method) -> bool:
        """ Check supported HTTP methods
        """
        return method in (
            QgsServerRequest.GetMethod, QgsServerRequest.PostMethod)

    def executeRequest(self, request: QgsServerRequest, response: QgsServerResponse,
                       project: QgsProject) -> None:
        """ Execute a 'ATLAS' request
        """

        params = request.parameters()

        # noinspection PyBroadException
        try:
            request_param = params.get('REQUEST', '').lower()

            if request_param == 'getcapabilities':
                self.get_capabilities(params, response, project)
            elif request_param == 'getprint':

                # Set current Lizmap user and groups in the project before printing
                headers = request.headers()
                lizmap_user = get_lizmap_user_login(params, headers)
                lizmap_group = get_lizmap_groups(params, headers)
                custom_var = project.customVariables()
                if custom_var.get('lizmap_user', None) != lizmap_user:
                    self.logger.info("Adding user and group variables in the QGIS project")
                    custom_var['lizmap_user'] = lizmap_user
                    custom_var['lizmap_user_groups'] = list(lizmap_group)  # QGIS can't store a tuple
                    project.setCustomVariables(custom_var)

                self.get_print(params, response, project, lizmap_user, lizmap_group)
            else:
                raise AtlasPrintError(
                    400,
                    "Invalid REQUEST parameter: must be one of GetCapabilities, GetPrint, found '{}'".format(
                        request_param
                    ))

        except AtlasPrintError as err:
            err.formatResponse(response)
        except Exception:
            self.logger.critical("Unhandled exception:\n{}".format(traceback.format_exc()))
            err = AtlasPrintError(500, "Internal 'atlasprint' service error")
            err.formatResponse(response)
        finally:
            # Remove previous login
            self.logger.info("Removing user and group variables from the QGIS project")
            custom_var = project.customVariables()
            custom_var.pop('lizmap_user', None)
            custom_var.pop('lizmap_user_groups', None)
            project.setCustomVariables(custom_var)

    @staticmethod
    def get_capabilities(params: Dict[str, str], response: QgsServerResponse, project: QgsProject) -> None:
        """ Get atlas capabilities based on metadata file
        """
        _ = params, project
        plugin_name = 'atlasprint'
        body = {
            'status': 'success',
            'metadata': {
                'name': plugin_name,
                'version': pluginMetadata(plugin_name, 'version'),
            }
        }
        write_json_response(body, response)
        return

    def get_print(
            self,
            params: Dict[str, str],
            response: QgsServerResponse,
            project: QgsProject,
            lizmap_user: str,
            lizmap_user_group: tuple
    ) -> None:
        """ Get print document
        """

        template = params.get('TEMPLATE')
        feature_filter = params.get('EXP_FILTER', None)
        scale = params.get('SCALE')
        scales = params.get('SCALES')
        output_format = parse_output_format(params.get('FORMAT', params.get('format')))

        try:
            if not template:
                raise AtlasPrintException('TEMPLATE is required')

            if feature_filter:
                expression = QgsExpression(feature_filter)
                if expression.hasParserError():
                    raise AtlasPrintException(
                        'Expression is invalid: {}'.format(expression.parserErrorString()))

            if scale and scales:
                raise AtlasPrintException('SCALE and SCALES can not be used together.')

            if scale:
                try:
                    scale = int(scale)
                except ValueError:
                    raise AtlasPrintException('Invalid number in SCALE.')

            if scales:
                try:
                    scales = [int(scale) for scale in scales.split(',')]
                except ValueError:
                    raise AtlasPrintException('Invalid number in SCALES.')

            additional_params = {
                k: v for k, v in params.items() if k.upper() not in (
                    'TEMPLATE', 'EXP_FILTER', 'SCALE', 'SCALES', 'FORMAT', 'MAP', 'REQUEST', 'SERVICE',
                    'DPI', 'EXCEPTIONS', 'LAYER', 'LIZMAP_OVERRIDE_FILTER', 'TRANSPARENT', 'VERSION',
                    'LIZMAP_USER', 'LIZMAP_USER_GROUPS',  # See below for these two
                )
            }

            if lizmap_user:
                # Only if the user is connected
                additional_params['lizmap_user'] = lizmap_user
                additional_params['lizmap_user_groups'] = ','.join(lizmap_user_group)

            output_path = print_layout(
                project=project,
                layout_name=params['TEMPLATE'],
                output_format=output_format,
                scale=scale,
                scales=scales,
                feature_filter=feature_filter,
                **additional_params
            )
        except AtlasPrintException as e:
            raise AtlasPrintError(400, 'ATLAS - Error from the user while generating the PDF: {}'.format(e))
        except Exception:
            self.logger.critical("Unhandled exception:\n{}".format(traceback.format_exc()))
            raise AtlasPrintError(500, "Internal 'atlasprint' service error")

        path = Path(output_path)
        if not path.exists():
            raise AtlasPrintError(404, "ATLAS {} not found".format(output_format.name))

        # Send PDF
        response.setHeader('Content-Type', output_format.value)
        response.setStatusCode(200)
        try:
            response.write(path.read_bytes())
            path.unlink()
        except Exception:
            self.logger.critical("Error occurred while reading {} file".format(output_format.name))
            raise
