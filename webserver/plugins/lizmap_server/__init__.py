__copyright__ = 'Copyright 2022, 3Liz'
__license__ = 'GPL version 3'
__email__ = 'info@3liz.org'


# noinspection PyPep8Naming
def classFactory(iface):
    from qgis.PyQt.QtWidgets import QMessageBox

    class Nothing:

        def __init__(self, iface):
            """ In QGIS Desktop.
            :param iface: The QGIS Desktop interface
            """
            self.iface = iface

        def initGui(self):
            QMessageBox.warning(
                self.iface.mainWindow(),
                'Lizmap server plugin',
                '"Lizmap server" is a plugin designed <b>only</b> for QGIS Server.'
                '<br><br>'
                'This plugin must be installed on QGIS Server following the documentation about '
                '<a href="https://docs.qgis.org/latest/en/docs/server_manual/plugins.html">'
                'QGIS server side plugins'
                '</a>.'
                '<br><br>'
                'For QGIS Desktop, use the other plugin called "Lizmap".',
            )

        def unload(self):
            pass

    return Nothing(iface)


# noinspection PyPep8Naming
def serverClassFactory(serverIface):
    """Load Lizmap server class.

    :param serverIface: A QGIS Server interface instance.
    :type serverIface: QgsServerInterface
    """
    from lizmap_server.plugin import LizmapServer
    return LizmapServer(serverIface)
