__copyright__ = 'Copyright 2022, 3Liz'
__license__ = 'GPL version 3'
__email__ = 'info@3liz.org'

import logging
import sys
import warnings

from typing import NamedTuple, Union

from qgis.core import Qgis
from qgis.PyQt import Qt
from qgis.PyQt.QtCore import QRegularExpression
from qgis.PyQt.QtGui import QFontDatabase
from qgis.server import QgsServerOgcApi, QgsServerOgcApiHandler

# FCGI or others
from qgis.utils import pluginMetadata, server_active_plugins

from lizmap_server.exception import ServiceError
from lizmap_server.tools import check_environment_variable, to_bool

try:
    # Py-QGIS-Server
    # noinspection PyUnresolvedReferences
    from pyqgisserver.plugins import plugin_list, plugin_metadata
    PY_QGIS_SERVER_INSTALLED = True
except ImportError:
    PY_QGIS_SERVER_INSTALLED = False

with warnings.catch_warnings():
    warnings.filterwarnings("ignore", category=DeprecationWarning)
    from osgeo import gdal

LOGGER = logging.getLogger('Lizmap')


def is_py_qgis_server_used() -> bool:
    """ Check if the plugin lizmap_server is found in Py-QGIS-Server plugins. """
    if not PY_QGIS_SERVER_INSTALLED:
        return False

    if 'lizmap_server' in list(plugin_list()):
        return True

    # Py-QGIS-Server is installed
    # Lizmap server is currently executed by the server but the list of plugin itself returned by Py-QGIS-Server
    # does not contain 'lizmap_server'.
    # Therefore, it means the administrator has installed Py-QGIS-Server, but he is not using it.
    # We fall back on native QGIS server API.
    # https://github.com/3liz/lizmap-web-client/issues/3437
    return False


def plugins_installed(py_qgis_server: bool) -> list:
    """ List of plugins according to the server FCGI or Py-QGIS-Server. """
    if py_qgis_server:
        # From Py-QGIS-Server API
        return list(plugin_list())

    # From QGIS native API
    return server_active_plugins


def plugin_metadata_key(py_qgis_server: bool, name: str, key: str, ) -> str:
    """ Return the version for a given plugin. """
    unknown = 'unknown'
    # it seems configparser is transforming all keys as lowercase...
    if py_qgis_server:
        # From Py-QGIS-Server API
        metadata = plugin_metadata(name)
        value = metadata['general'].get(key, None)
        if value:
            return value
        return metadata['general'].get(key.lower(), unknown)
    else:
        # From QGIS native API
        value = pluginMetadata(name, key)
        if value not in ("__error__", ""):
            return value
        value = pluginMetadata(name.lower(), key)
        if value not in ("__error__", ""):
            return value
        return unknown


PyQgisServer = NamedTuple(
    "PyQgisServer", [
        ('version', str),
        ('build_id', Union[int, None]),
        ('commit_id', Union[int, None]),
        ('is_stable', bool)
    ]
)


def py_qgis_server_info(py_qgis_server_used: bool) -> PyQgisServer:
    """ Return the Py-QGIS-Server version or an empty string. """
    version = 'not used'
    build_id = None
    commit_id = None
    is_stable = False
    if not py_qgis_server_used:
        return PyQgisServer(version, build_id, commit_id, is_stable)

    # noinspection PyBroadException
    try:
        from pyqgisserver.version import __manifest__, __version__
        version = __version__
        build_id = __manifest__.get('buildid')
        commit_id = __manifest__.get('commitid')
        is_stable = not any(x in version for x in ("pre", "alpha", "beta", "rc"))
        return PyQgisServer(version, build_id, commit_id, is_stable)
    except Exception:
        msg = 'error while fetching py-qgis-server version'
        LOGGER.error(msg)
        return PyQgisServer(msg, build_id, commit_id, is_stable)


class ServerInfoHandler(QgsServerOgcApiHandler):

    def path(self):
        return QRegularExpression("server.json")

    def summary(self):
        return "Server information"

    def description(self):
        return "Get info about the current QGIS server"

    def operationId(self):
        return "server"

    def linkTitle(self):
        return "Handler Lizmap API server info"

    def linkType(self):
        return QgsServerOgcApi.data

    def handleRequest(self, context):
        if not check_environment_variable():
            raise ServiceError("Bad request error", "Invalid request", 404)

        is_py_qgis_server = is_py_qgis_server_used()
        py_qgis_server_metadata = py_qgis_server_info(is_py_qgis_server)

        # 'name' is not the folder name in the 'expected_list' variable, it can be different
        keys = ('name', 'version', 'commitNumber', 'commitSha1', 'dateTime', 'repository')
        plugins = dict()
        for plugin in plugins_installed(is_py_qgis_server):
            plugins[plugin] = dict()
            for key in keys:
                plugins[plugin][key] = plugin_metadata_key(is_py_qgis_server, plugin, key)

        expected_list = (
            'wfsOutputExtension',
            # 'cadastre', very specific for the French use-case
            'lizmap_server',
            'atlasprint',
            # waiting a little for these one
            # 'tilesForServer',
            # 'DataPlotly',
        )

        for expected in expected_list:
            if expected not in plugins.keys():
                plugins[expected] = {
                    'version': 'not found',
                    'name': expected,
                }

        # 3.28 : Firenze
        # 3.30 : 's-Hertogenbosch
        human_version, human_name = Qgis.QGIS_VERSION.split('-', 1)

        services_available = []
        expected_services = ('WMS', 'WFS', 'WCS', 'WMTS', 'ATLAS', 'CADASTRE', 'EXPRESSION', 'LIZMAP')
        for service in expected_services:
            if context.serverInterface().serviceRegistry().getService(service):
                services_available.append(service)

        if Qgis.QGIS_VERSION_INT >= 31200 and Qgis.devVersion() != 'exported':
            commit_id = Qgis.devVersion()
        else:
            commit_id = ''

        # noinspection PyBroadException
        try:
            # Format the tag according to QGIS git repository
            tag = 'final-{}'.format(human_version.replace('.', '_'))  # final-3_16_0
        except Exception:
            tag = ""

        data = {
            # Only the "qgis_server" section is forwarded in LWC source code
            'qgis_server': {
                'metadata': {
                    'version': human_version,  # 3.16.0
                    'tag': tag,  # final-3_16_0
                    'name': human_name,  # Hannover
                    'commit_id': commit_id,  # 288d2cacb5 if it's a dev version
                    'version_int': Qgis.QGIS_VERSION_INT,  # 31600
                },
                'py_qgis_server': {
                    'found': is_py_qgis_server,
                    'version': py_qgis_server_metadata.version,
                    'build_id': py_qgis_server_metadata.build_id,
                    'commit_id': py_qgis_server_metadata.commit_id,
                    'stable': py_qgis_server_metadata.is_stable,
                },
                # 'support_custom_headers': self.support_custom_headers(),
                'services': services_available,
                'plugins': plugins,
                'fonts': QFontDatabase().families(),
            },
            'environment': {
                'gdal': gdal.VersionInfo('VERSION_NUM'),
                'python': sys.version,
                'qt': Qt.QT_VERSION_STR,
            }
        }
        self.write(data, context)

    def support_custom_headers(self) -> Union[None, bool]:
        """ Check if this QGIS Server supports custom headers.

         Returns None if the check is not requested with the GET parameter CHECK_CUSTOM_HEADERS

         If requested, returns boolean if X-Check-Custom-Headers is found in headers.
         """
        handler = self.serverIface().requestHandler()

        params = handler.parameterMap()
        if not to_bool(params.get('CHECK_CUSTOM_HEADERS')):
            return None

        headers = handler.requestHeaders()
        return headers.get('X-Check-Custom-Headers') is not None

    def parameters(self, context):
        from qgis.server import QgsServerQueryStringParameter
        return [
            QgsServerQueryStringParameter(
                "CHECK_CUSTOM_HEADERS",
                False,
                QgsServerQueryStringParameter.Type.String,
                "If we check custom headers"
            ),
        ]
