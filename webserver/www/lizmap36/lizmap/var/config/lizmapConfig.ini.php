;<?php die(''); ?>
;for security reasons , don't remove or modify the previous line

;Services
;list the different map services (servers, generic parameters, etc.)
[services]
;Wms map server
wmsServerURL="http://localhost/qgis/qgis_mapserv.fcgi.exe"

onlyMaps=0

; cache configuration for tiles
cacheStorageType=file
;cacheStorageType=sqlite => store cached images in one sqlite file per repo/project/layer
;cacheStorageType=file => store cached images in one folder per repo/project/layer. The root folder is /tmp/
;cacheStorageType=redis => store cached images through redis
cacheRedisHost=localhost
cacheRedisPort=6379

; default cache expiration : the default time to live of data, in seconds.
; 0 means no expiration, max : 2592000 seconds (30 days)
cacheExpiration=0

; debug mode
; on = print debug messages in lizmap/var/log/messages.log
; off = no lizmap debug messages
debugMode=0
; cache root directory where cache files will be stored
; must be writable
cacheRootDirectory="C:\webserver\tmp"

; path to find repositories
;rootRepositories="path"


; Does the server use relative path from root folder? 0/1
;relativeWMSPath=0

appName=Lizmap
qgisServerVersion=3.0
wmsMaxWidth=3000
wmsMaxHeight=3000
projectSwitcher=off
relativeWMSPath=0
requestProxyEnabled=0
requestProxyType=http
requestProxyNotForDomain="localhost,127.0.0.1"
uploadedImageMaxWidthHeight=1920

[repository:repo1]
label="Repository #1"
path="C:/webserver/lm_repos/repo1/"
allowUserDefinedThemes=1
accessControlAllowOrigin=

[repository:demos]
label="Demos Lizmap"
path="C:/webserver/lm_repos/demos_lizmap/"
allowUserDefinedThemes=0
accessControlAllowOrigin=
