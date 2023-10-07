<?php return array (
  'header.admin' => 'Administration',
  'menu.configuration.main.label' => 'Lizmap configuration',
  'menu.server.information.label' => 'Server information',
  'menu.lizmap.repositories.label' => 'Maps management',
  'menu.lizmap.project.list.label' => 'QGIS projects',
  'menu.lizmap.landingPageContent.label' => 'Landing page content',
  'menu.lizmap.logs.label' => 'Lizmap Logs',
  'menu.lizmap.logs.view.label' => 'View logs',
  'menu.server.label' => 'Server',
  'generic.h2' => 'Generic',
  'generic.version.number.label' => 'Version number',
  'configuration.h1' => 'Lizmap configuration',
  'configuration.repository.label' => 'Repository',
  'configuration.button.back.label' => 'Back',
  'configuration.services.label' => 'Services',
  'configuration.services.section.interface.label' => 'Interface',
  'configuration.services.section.emails.label' => 'Emails',
  'configuration.services.section.projects.label' => 'Projects',
  'configuration.services.section.cache.label' => 'Cache',
  'configuration.services.section.qgis.label' => 'Qgis server',
  'configuration.services.section.system.label' => 'System',
  'configuration.services.section.features.label' => 'GIS features properties',
  'configuration.services.qgisServerVersion.label' => 'QGIS server version',
  'configuration.services.wmsServerURL.label' => 'WMS server URL',
  'configuration.services.wmsPublicUrlList.label' => 'WMS subdomain URLs list (optional)',
  'configuration.services.wmsMaxWidth.label' => 'Maximum Width for GetMap request',
  'configuration.services.wmsMaxHeight.label' => 'Maximum Height for GetMap request',
  'configuration.services.onlyMaps.label' => 'Only maps',
  'configuration.services.defaultRepository.label' => 'Default repository',
  'configuration.services.defaultProject.label' => 'Default project',
  'configuration.services.cacheStorageType.label' => 'Server cache storage type',
  'configuration.services.cacheRootDirectory.label' => 'Cache root directory',
  'configuration.services.cacheExpiration.label' => 'Server cache expiration time(s)',
  'configuration.services.rootRepositories.label' => 'Root folder of repositories',
  'configuration.services.debugMode.label' => 'Debug mode',
  'configuration.services.allowUserAccountRequests.label' => 'Allow visitors to request an account',
  'configuration.services.allowUserAccountRequests.help.deactivated' => 'This option is deactivated because of the use of Ldap authentication',
  'configuration.services.adminContactEmail.label' => 'Administrator e-mail',
  'config.services.allowUserAccountRequest.noemail' => 'This option cannot be enabled while the sender email is not defined. Please contact the application administrator.',
  'configuration.button.modify.service.label' => 'Modify',
  'configuration.button.view.repository.label' => 'View',
  'configuration.button.modify.repository.label' => 'Modify',
  'configuration.button.remove.repository.label' => 'Remove',
  'configuration.button.remove.repository.confirm.label' => 'Would you like to remove this repository?',
  'configuration.button.add.repository.label' => 'Create a repository',
  'form.admin_services.h1' => 'Modify Lizmap generic configuration',
  'form.admin_services.on.label' => 'On',
  'form.admin_services.off.label' => 'Off',
  'form.admin_services.yes.label' => 'Yes',
  'form.admin_services.no.label' => 'No',
  'form.admin_services.appName.label' => 'Application name',
  'form.admin_services.qgisServerVersion.label' => 'QGIS server version',
  'form.admin_services.qgisServerVersion.214.label' => '≤ 2.14',
  'form.admin_services.qgisServerVersion.218.label' => '2.18',
  'form.admin_services.qgisServerVersion.300.label' => '≥ 3.0',
  'form.admin_services.wmsServerURL.label' => 'WMS server URL',
  'form.admin_services.wmsPublicUrlList.label' => 'WMS subdomain URLs list (optional)',
  'form.admin_services.message.wmsPublicUrlList.wrong' => 'The WMS subdomain URLs structure is not valid.',
  'form.admin_services.wmsMaxWidth.label' => 'Default max. Width for GetMap request',
  'form.admin_services.wmsMaxHeight.label' => 'Default max. Height for GetMap request',
  'form.admin_services.lizmapPluginAPIURL.label' => 'Base URL of the Lizmap plugin API for QGIS Server',
  'form.admin_services.lizmapPluginAPIURL.help' => 'QGIS Server %s or above is required, as well as the Lizmap server plugin %s or above. If you are using the FCGI server, you must let empty this field. Else if you are using Py-Qgis-Server, indicate the URL configured into the Py-Qgis-Server configuration about the Lizmap plugin URL.',
  'form.admin_services.cacheStorageType.label' => 'Server cache storage type',
  'form.admin_services.cacheStorageType.sqlite.label' => 'SQLite database',
  'form.admin_services.cacheStorageType.file.label' => 'Files',
  'form.admin_services.cacheStorageType.redis.label' => 'Redis',
  'form.admin_services.cacheRedisHost.label' => 'Redis host',
  'form.admin_services.cacheRedisPort.label' => 'Redis port',
  'form.admin_services.cacheRedisDb.label' => 'Redis database index',
  'form.admin_services.cacheRedisKeyPrefix.label' => 'Redis key prefix',
  'form.admin_services.cacheRedisHost.help' => 'Redis host (used if Redis is chosen for the cache storage)',
  'form.admin_services.cacheRedisPort.help' => 'Redis port (used if Redis is chosen for the cache storage)',
  'form.admin_services.cacheRedisDb.help' => 'Redis database index: integer ≥ 0, optional (used if Redis is chosen for the cache storage)',
  'form.admin_services.cacheRedisKeyPrefix.help' => 'Redis key prefix: string, optional (used if Redis is chosen for the cache storage)',
  'form.admin_services.cacheRootDirectory.label' => 'Cache root directory',
  'form.admin_services.cacheRootDirectory.help' => 'Choose a writable directory where to store cache files.',
  'form.admin_services.message.cacheRootDirectory.wrong' => 'The directory does not exist or is not writable. You can use: %s',
  'form.admin_services.cacheExpiration.label' => 'Server cache expiration time(s)',
  'form.admin_services.cacheExpiration.help' => '0 means no expiration, else give an integer in seconds (max: 2592000 s = 30 days)',
  'form.admin_services.message.cacheExpiration.wrong' => 'Server cache expiration time must be an integer between 0 and 2592000 seconds.',
  'form.admin_services.rootRepositories.label' => 'Root folder of repositories',
  'form.admin_services.rootRepositories.help' => 'The root folder of repositories can limit the Lizmap directory to 1 folder. If a path is set here, it is no longer possible to define the path of a Lizmap directory.',
  'form.admin_services.relativeWMSPath.label' => 'Does the server use a relative path from the root folder?',
  'form.admin_services.requestProxy.label' => 'Use a proxy server to do requests to external services',
  'form.admin_services.requestProxy.enabled' => 'Use a proxy server',
  'form.admin_services.requestProxy.disabled' => 'No proxy',
  'form.admin_services.requestProxyHost.label' => 'Host of the proxy server',
  'form.admin_services.requestProxyPort.label' => 'Port of the proxy server',
  'form.admin_services.requestProxyType.label' => 'Type of the proxy',
  'form.admin_services.requestProxyUser.label' => 'Login to access to the proxy server',
  'form.admin_services.requestProxyPassword.label' => 'Password to access to the proxy server',
  'form.admin_services.requestProxyNotForDomain.label' => 'Domains for which the proxy will not be used',
  'form.admin_services.debugMode.label' => 'Debug mode',
  'form.admin_services.debugMode.0.label' => 'Off',
  'form.admin_services.debugMode.1.label' => 'Log',
  'form.admin_services.onlyMaps.label' => 'Only maps',
  'form.admin_services.projectSwitcher.label' => 'Show projects switcher',
  'form.admin_services.defaultRepository.label' => 'Default repository',
  'form.admin_services.defaultProject.label' => 'Default project',
  'form.admin_services.allowUserAccountRequests.label' => 'Allow visitors to request an account?',
  'form.admin_services.allowUserAccountRequests.help' => 'If set to on, the visitors can fill a form to request an account. Sender address for e-mails should be set.',
  'form.admin_services.adminContactEmail.label' => 'E-mail where to send notifications',
  'form.admin_services.adminContactEmail.help' => 'E-mail address to which notifications will be sent',
  'form.admin_services.adminSenderEmail.label' => 'Sender address of e-mails',
  'form.admin_services.adminSenderEmail.help' => 'E-mail address used to send e-mails. If empty, no emails will be sent by Lizmap. Be sure your server is allowed to send email with this address.',
  'form.admin_services.adminSenderEmail.readonly.help' => 'E-mail address used to send e-mails. It cannot be modified.',
  'form.admin_services.adminSenderEmail.error.required' => 'A sender e-mail address is required to send notification or requests for account creation',
  'form.admin_services.adminSenderName.label' => 'Sender name of e-mails',
  'form.admin_services.adminSenderName.help' => 'Name of the sender used to send e-mails.',
  'form.admin_services.emails.help' => 'To send emails, Lizmap needs a sender address. If empty, no emails will be sent by Lizmap, and therefore, some features like password recovery or email notifications won\'t work.',
  'form.admin_services.emails.server.help' => 'Be sure the web server is able to send emails or setup SMTP parameters into the configuration files of Lizmap.',
  'form.admin_services.emails.no.server' => 'No sender address is configured in Lizmap. Some features like password recovery or email notifications cannot work. Contact the application administrator if you want to enable all email features.',
  'form.admin_services.googleAnalyticsID.label' => 'Google Analytics ID',
  'form.admin_services.googleAnalyticsID.help' => 'The Google Analytics ID is of the form \'UA-XXXX-Y\'. It\'s also called the "tracking ID".',
  'form.admin_services.uploadedImageMaxWidthHeight.label' => 'Maximum width/height of uploaded images for features',
  'form.admin_services.uploadedImageMaxWidthHeight.help' => 'Images uploaded when editing features will have this maximum width and height (in pixels). A recommended value is 1920 (HD size). The maximum value is 3840 and the minimum value is 480.',
  'form.admin_services.submit.label' => 'Save',
  'form.admin_services.message.data.saved' => 'Data has been saved.',
  'form.admin_section.h1.create' => 'Create a repository',
  'form.admin_section.h1.modify' => 'Modify the repository',
  'form.admin_section.repository.label' => 'ID',
  'form.admin_section.repository.help' => 'Repository unique identifier, it must only contain characters between a-z and 0-9 (no space, no special character). This identifier can not be edited later.',
  'form.admin_section.repository.label.label' => 'Label',
  'form.admin_section.repository.label.help' => 'Repository label, which will be displayed in the interface',
  'form.admin_section.repository.path.label' => 'Local folder path',
  'form.admin_section.repository.path.help' => 'The directory in which QGIS projects and Lizmap configuration file must be stored.',
  'form.admin_section.repository.allowUserDefinedThemes.label' => 'Allow themes/javascript codes for this repository',
  'form.admin_section.repository.accessControlAllowOrigin.label' => 'List of websites allowed to access the map services (WMS/WMTS/WFS...)',
  'form.admin_section.repository.accessControlAllowOrigin.help' => 'List of URL separated by a comma, like \'https://domain1.com,https://www.domain2.com\'. Path and query parameters will be removed from the URL.',
  'form.admin_section.submit.label' => 'Save',
  'form.admin_section.data.label' => 'Data config:',
  'form.admin_section.groups.label' => 'Rights and granted groups:',
  'form.admin_section.grouplist.label' => 'List of groups',
  'form.admin_section.message.data.saved' => 'The repository data has been saved.',
  'form.admin_section.message.configure.rights' => 'You can now configure the rights for this repository.',
  'form.admin_section.message.data.removed' => 'The repository has been removed',
  'form.admin_section.message.data.removed.groups.concerned' => '(%s group(s) concerned)',
  'form.admin_section.message.data.removed.failed' => 'The repository has not been removed',
  'form.admin_section.message.repository.wrong' => 'The repository\'s id given does not exist.',
  'form.admin_section.message.path.wrong' => 'The path given for the repository does not exist on the server.',
  'form.admin_section.message.path.not_authorized' => 'The path given for the repository is not authorized.',
  'form.admin_section.message.accessControlAllowOrigin.bad.domain' => 'Content is not a valid URL or a list of URLs.',
  'menu.lizmap.theme.label' => 'Theme',
  'configuration.button.modify.theme.label' => 'Modify',
  'form.admin_theme.h1' => 'Theme',
  'form.admin_theme.helpcolor' => 'You can select colors with this <a href="https://mdn.github.io/css-examples/tools/color-picker/" target="_blank">colorpicker</a> (e.g. <code>rgb(148 193 31 / 0.5)</code>).',
  'form.admin_theme.headerLogo.label' => 'Header - logo',
  'form.admin_theme.headerLogo.help' => 'The image to be used for the header logo (200 ko maximum).',
  'form.admin_theme.headerLogoWidth.label' => 'Header - logo width',
  'form.admin_theme.headerLogoWidth.help' => 'The width taken by the logo before the title (in pixels).',
  'form.admin_theme.headerBackgroundImage.label' => 'Header - background image',
  'form.admin_theme.headerBackgroundImage.help' => 'The image to be used for the header background (400 ko maximum).',
  'form.admin_theme.headerBackgroundColor.label' => 'Header - background color',
  'form.admin_theme.headerBackgroundColor.help' => 'The color of the header background.',
  'form.admin_theme.headerTitleColor.label' => 'Header - title color',
  'form.admin_theme.headerTitleColor.help' => 'The color of the header title text.',
  'form.admin_theme.headerSubtitleColor.label' => 'Header - subtitle color',
  'form.admin_theme.headerSubtitleColor.help' => 'The color of the header subtitle text.',
  'form.admin_theme.menuBackgroundColor.label' => 'Menu bar - background color',
  'form.admin_theme.menuBackgroundColor.help' => 'The color of the menu left bar background.',
  'form.admin_theme.dockBackgroundColor.label' => 'Docks - background color',
  'form.admin_theme.dockBackgroundColor.help' => 'The color of the docks background.',
  'form.admin_theme.navbarColor.label' => 'Navigation bar - background color',
  'form.admin_theme.navbarColor.help' => 'The background color of the navigation bar (zoom tools).',
  'form.admin_theme.additionalCss.label' => 'Additional CSS properties',
  'form.admin_theme.additionalCss.help' => 'You can write any CSS syntax to override Lizmap style (for advanced users)',
  'theme.detail.title' => 'Theme configuration',
  'theme.button.remove.logo.label' => 'Remove',
  'theme.button.remove.logo.confirm.label' => 'Remove this image (and use the default one)?',
  'theme.error.no.access' => 'You have no right to access the theme configuration page.',
  'cache.repository.removed' => 'The cache for the repository %s has been successfully emptied.',
  'cache.layer.removed' => 'The cache for the layer %s has been successfully emptied.',
  'cache.button.remove.repository.cache.confirm.label' => 'Would you like to empty the cache for this repository?',
  'cache.button.remove.repository.cache.label' => 'Empty cache',
  'logs.counter.title' => 'Log count',
  'logs.detail.title' => 'Log detail',
  'logs.counter.number.sentence' => 'lines in the global count log table',
  'logs.detail.number.sentence' => 'lines in the detail log table',
  'logs.view.button' => 'View',
  'logs.export.button' => 'Export',
  'logs.empty.button' => 'Empty',
  'logs.empty.confirm' => 'Are you sure to want to empty the log content?',
  'logs.empty.ok' => 'The log table %s has been successfully emptied.',
  'logs.key' => 'Key',
  'logs.counter' => 'Count',
  'logs.timestamp' => 'Timestamp',
  'logs.user' => 'User',
  'logs.content' => 'Content',
  'logs.repository' => 'Repository',
  'logs.project' => 'Project',
  'logs.ip' => 'IP',
  'logs.first_page' => 'First',
  'logs.previous_page' => 'Previous',
  'logs.next_page' => 'Next',
  'logs.email.subject' => 'A new log item has been recorded.',
  'logs.email.login.body' => 'A user has logged in.',
  'logs.email.viewmap.body' => 'A user has accessed a map.',
  'logs.email.print.body' => 'A user has used the print tool.',
  'logs.email.popup.body' => 'A user has opened a feature information popup.',
  'logs.email.editionSaveFeature.body' => 'A user has created or modified a feature.',
  'logs.email.editionDeleteFeature.body' => 'A user has deleted a feature.',
  'logs.error.title' => 'Error log',
  'logs.error.sentence' => 'Last lines of the Lizmap Web Client application error log file',
  'logs.error.file.too.big' => 'The error log file is too big. Consider using log rotation for Lizmap Web Client logs.',
  'logs.error.file.erase' => 'Erase the error log file',
  'logs.error.file.erase.confirm' => 'Are you sure you want to erase the error log file?',
  'logs.error.file.erase.ok' => 'The error log file has successfully been erased.',
  'logs.error.no.delete.right' => 'You have no right to delete the logs.',
  'upload.image.error.file.missing' => 'File is missing',
  'upload.image.error.file.bigger' => 'The uploaded file exceeds the maximum file size allowed',
  'upload.image.error.file.partially' => 'The uploaded file was only partially uploaded',
  'upload.image.error.file.none' => 'No file was uploaded',
  'upload.image.error.missing.temp' => 'Missing a temporary folder',
  'upload.image.error.file.onDisk' => 'Failed to write file to disk',
  'upload.image.error.file.invalid' => 'Invalid file',
  'upload.image.error.file.wrongType' => 'Wrong file type. Only images are allowed',
  'upload.image.error.file.save' => 'An error occurred during the save',
  'server.information.lizmap.label' => 'Lizmap Web Client',
  'server.information.lizmap.info' => 'Information',
  'server.information.lizmap.info.version' => 'Version',
  'server.information.lizmap.info.date' => 'Date',
  'server.information.lizmap.url' => 'URL in the QGIS desktop plugin',
  'server.information.qgis.label' => 'QGIS Server',
  'server.information.qgis.metadata' => 'Version',
  'server.information.qgis.version' => 'Number',
  'server.information.qgis.name' => 'Name',
  'server.information.qgis.commit_id' => 'Commit ID',
  'server.information.qgis.action' => 'Action',
  'server.information.qgis.plugins' => 'Plugins',
  'server.information.qgis.plugins.version' => 'Version number',
  'server.information.qgis.plugin' => 'Plugin',
  'server.information.qgis.plugin.version' => 'Version',
  'server.information.qgis.plugin.action' => 'Action',
  'server.information.qgis.test.ok' => 'QGIS Server is correctly installed and returns the expected response for OGC requests.',
  'server.information.qgis.test.error' => 'QGIS Server is not correctly installed in your server or the URL for OGC requests given in Lizmap configuration is not correct.',
  'server.information.qgis.error.fetching.information' => 'We cannot get the details about your QGIS Server installation (version, plugins, etc.).',
  'server.information.qgis.error.fetching.information.description' => 'You must check that the following steps are done :',
  'server.information.qgis.error.fetching.information.qgis.version.html' => '<strong>QGIS Server</strong> is above or equal to <strong>%s</strong>',
  'server.information.qgis.error.fetching.information.plugin.version.html' => 'The <strong>Lizmap QGIS server plugin</strong> is installed on the server with a version above or equal to <strong>%s</strong>',
  'server.information.qgis.error.fetching.information.qgis.url.html' => 'The URL to reach QGIS server is <strong>correct</strong> in your administration panel',
  'server.information.qgis.error.fetching.information.qgis.lizmap.html' => 'The URL to reach the Lizmap API on QGIS Server is <strong>correct</strong> in your administration panel, if you use Py-QGIS-Server',
  'server.information.qgis.error.fetching.information.lizmap.logs.html' => 'Your Lizmap Web Client <strong>logs</strong> does not contain any warnings about reaching QGIS server',
  'server.information.qgis.error.fetching.information.environment.variable' => 'The <strong>environment variable</strong> about the <strong>Lizmap server plugin</strong> described in the documentation link below is correctly set in your configuration',
  'server.information.qgis.error.fetching.information.help' => 'Your QGIS server <strong>logs</strong> does not contain any warnings about the Lizmap server plugin (at QGIS server startup and when loading this webpage). You might need to increase debug level.',
  'server.information.qgis.error.fetching.information.detail.NO_ACCESS' => 'You don\'t have access to information about QGIS Server',
  'server.information.qgis.error.fetching.information.detail.HTTP_ERROR' => 'QGIS Server returns an HTTP error about the Lizmap plugin:',
  'server.information.qgis.update' => 'QGIS Server needs to be updated at least to version %s for this version of Lizmap Web Client.',
  'server.information.plugin.update' => 'The %s plugin needs to be updated.',
  'server.information.qgis.unknown' => 'QGIS server minimum %s and Lizmap QGIS server plugin minimum %s need to be installed and configured correctly. Your QGIS server couldn\'t be reached correctly with the given URL "%s".',
  'server.information.error' => 'Before checking your projects, you must check your QGIS server settings in the "Server information" menu. The configuration of QGIS Server is currently not correct.',
  'project.error.some.projects.not.displayed' => 'Some projects, listed below, can not be displayed in the main web interface because they need an update from the GIS administrator. Please read the guidelines about colours by clicking on the button "%s".',
  'project.rules.list.introduction' => 'Explanation about colours used in the table above',
  'project.rules.list.description.html' => 'Given your current Lizmap Web Client version <strong>%s</strong> and QGIS Server version <strong>%s</strong>, there are some rules about the table above :',
  'project.rules.list.warnings.html' => '<strong>Warnings</strong>, projects are still visible, but you should have a look to these projects :',
  'project.rules.list.qgis.version.warning.html' => 'The project version is too old compared to the current QGIS Server version. Current value less or equal to <strong>%s</strong>.',
  'project.rules.list.qgis.version.error.html' => 'The desktop version <strong>is above</strong> QGIS server. Current value greater or equal than <strong>%s</strong>.',
  'project.rules.list.target.version.html' => 'The project is designed for Lizmap Web Client <strong>%s</strong>.',
  'project.rules.list.important.count.layers.html' => 'The count is greater or equal to <strong>%s</strong> layers.',
  'project.rules.list.very.important.count.layers.html' => 'The count is greater or equal to <strong>%s</strong> layers.',
  'project.rules.list.custom.projection' => 'The project is using a <strong>user-defined</strong> projection.',
  'project.rules.list.invalid.datasource.html' => 'The project has some <strong>invalid layer datasource</strong> when QGIS server loaded the project.',
  'project.rules.list.warning.loading.html' => 'The project takes longer than <strong>%s</strong> seconds to load.',
  'project.rules.list.error.loading.html' => 'The project takes longer than <strong>%s</strong> seconds to load.',
  'project.rules.list.warning.memory.html' => 'The project takes more than <strong>%s</strong> Mo of the server memory to load.',
  'project.rules.list.error.memory.html' => 'The project takes more than <strong>%s</strong> Mo of the server memory to load.',
  'project.rules.list.blocking.html' => '<strong>Blocking</strong>, these projects are not visible anymore :',
  'project.rules.list.blocking.description' => 'Some projects are not visible anymore without any updates from the GIS administrator.',
  'project.rules.list.blocking.target.html' => 'The project is designed for a Lizmap Web Client version equal or less than <strong>%s</strong>.',
  'project.list.column.repository.label' => 'Repository',
  'project.list.column.project.label' => 'Project',
  'project.list.column.inspection.file.time.label' => 'Inspection time',
  'project.list.column.project.abstract.label' => 'Abstract',
  'project.list.column.project.has.log.label' => 'QGIS Server logs',
  'project.list.column.project.qgis.log.label' => 'QGIS Server logs',
  'project.list.column.project.file.time.label' => 'Last modified',
  'project.list.column.crs.label' => 'Projection',
  'project.list.column.crs.user.warning.label' => 'Please avoid user-defined projections for projects and layers.',
  'project.list.column.layers.count.label' => 'Layers',
  'project.list.column.layers.count.label.longer' => 'Layer count',
  'project.list.column.layers.count.warning.label' => 'This project has many layers. Performance could be degraded.',
  'project.list.column.layers.count.error.label' => 'This project has a huge number of layers. Performance could be highly degraded.',
  'project.list.column.qgis.desktop.version.label' => 'QGIS Desktop',
  'project.list.column.qgis.desktop.version.above.server' => 'The QGIS project has been saved with a QGIS desktop version above the QGIS server installed in your server',
  'project.list.column.qgis.desktop.version.too.old' => 'The QGIS project has been saved with an old version of QGIS desktop',
  'project.list.column.update.in.qgis.desktop' => 'This project can not be displayed in the landing page. Open the project in QGIS desktop with an updated version of the plugin',
  'project.list.column.update.soon.in.qgis.desktop' => 'In the next version of Lizmap Web Client, this project won\'t be displayed because it has been designed for a too old version of Lizmap Web Client. Open the project in QGIS desktop with an updated version of the plugin',
  'project.list.column.target.lizmap.version.label' => 'Target version',
  'project.list.column.target.lizmap.version.label.longer' => 'Target version of Lizmap Web Client',
  'project.list.column.lizmap.plugin.version.label' => 'Lizmap plugin',
  'project.list.column.authorized.groups.label' => 'Groups',
  'project.list.column.hidden.project.label' => 'Hidden',
  'project.list.column.hidden.project.yes.label' => 'Yes',
  'project.list.column.hidden.project.no.label' => 'No',
  'project.list.column.loading.time.label' => 'Loading time (s)',
  'project.list.column.loading.time.label.alt' => 'Loading time',
  'project.list.column.loading.time.warning.label' => 'The project loading time is a bit long.',
  'project.list.column.loading.time.error.label' => 'The project loading time is very long. Please try to simplify your QGIS project for better performance.',
  'project.list.column.memory.usage.label' => 'Memory (Mo)',
  'project.list.column.memory.usage.label.alt' => 'Memory',
  'project.list.column.memory.usage.warning.label' => 'The project memory usage is a bit high.',
  'project.list.column.memory.usage.error.label' => 'The project memory usage is very high. Please try to simplify your QGIS project for better performance.',
  'project.list.column.invalid.layers.count.label' => 'Invalid layers',
  'project.list.column.invalid.layers.list.label' => 'List of invalid layers',
  'project.list.column.invalid.layers.count.error.label' => 'QGIS cannot read some layers. Please check that the server can access the layer data. 
* For file based layers, the path must be accessible. 
* For PostgreSQL layers, the Lizmap server must be able to connect to the database server.',
  'project.list.column.layout.count.label' => 'Layouts',
  'project.list.column.show.line.hidden.columns' => 'Show/Hide the hidden columns',
  'project.modal.title' => 'Table legend',
  'project.modal.button.close' => 'Close',
  'project.list.no.hidden.column.content' => 'No extra content for this project in the following hidden table columns',
);
