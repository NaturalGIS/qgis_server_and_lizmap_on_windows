__copyright__ = 'Copyright 2022, 3Liz'
__license__ = 'GPL version 3'
__email__ = 'info@3liz.org'

from qgis.server import QgsServerInterface, QgsServerOgcApi

from lizmap_server.expression_service import ExpressionService
from lizmap_server.get_feature_info import GetFeatureInfoFilter
from lizmap_server.get_legend_graphic import GetLegendGraphicFilter
from lizmap_server.legend_onoff_filter import LegendOnOffFilter
from lizmap_server.lizmap_accesscontrol import LizmapAccessControlFilter
from lizmap_server.lizmap_filter import LizmapFilter
from lizmap_server.lizmap_service import LizmapService
from lizmap_server.logger import Logger
from lizmap_server.server_info_handler import ServerInfoHandler
from lizmap_server.tools import check_environment_variable, version


class LizmapServer:
    """Plugin for QGIS server
    this plugin loads Lizmap filter"""

    def __init__(self, server_iface: QgsServerInterface) -> None:
        self.server_iface = server_iface
        self.logger = Logger()
        self.version = version()
        self.logger.info('Init server version "{}"'.format(self.version))

        service_registry = server_iface.serviceRegistry()

        # Register API
        lizmap_api = QgsServerOgcApi(
            self.server_iface,
            '/lizmap',
            'Lizmap',
            'The Lizmap API endpoint',
            self.version)
        service_registry.registerApi(lizmap_api)
        lizmap_api.registerHandler(ServerInfoHandler())
        self.logger.info('API "/lizmap" loaded with the server info handler')

        check_environment_variable()

        # Register service
        try:
            service_registry.registerService(ExpressionService())
        except Exception as e:
            self.logger.critical('Error loading service "expression" : {}'.format(e))
            raise
        self.logger.info('Service "expression" loaded')

        try:
            service_registry.registerService(LizmapService(self.server_iface))
        except Exception as e:
            self.logger.critical('Error loading service "lizmap" : {}'.format(e))
            raise
        self.logger.info('Service "lizmap" loaded')

        try:
            server_iface.registerFilter(LizmapFilter(self.server_iface), 50)
        except Exception as e:
            self.logger.critical('Error loading filter "lizmap" : {}'.format(e))
            raise
        self.logger.info('Filter "lizmap" loaded')

        try:
            server_iface.registerAccessControl(LizmapAccessControlFilter(self.server_iface), 100)
        except Exception as e:
            self.logger.critical('Error loading access control "lizmap" : {}'.format(e))
            raise
        self.logger.info('Access control "lizmap" loaded')

        try:
            server_iface.registerFilter(GetFeatureInfoFilter(self.server_iface), 150)
        except Exception as e:
            self.logger.critical('Error loading filter "get feature info" : {}'.format(e))
            raise
        self.logger.info('Filter "get feature info" loaded')

        try:
            server_iface.registerFilter(GetLegendGraphicFilter(self.server_iface), 170)
        except Exception as e:
            self.logger.critical('Error loading filter "get legend graphic" : {}'.format(e))
            raise
        self.logger.info('Filter "get legend graphic" loaded')

        try:
            server_iface.registerFilter(LegendOnOffFilter(self.server_iface), 175)
        except Exception as e:
            self.logger.critical('Error loading filter "legend on/off" : {}'.format(e))
            raise
        self.logger.info('Filter "legend on/off" loaded')
