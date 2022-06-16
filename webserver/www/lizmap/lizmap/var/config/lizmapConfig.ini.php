;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

;Services
;list the different map services (servers, generic parameters, etc.)
[services]
wmsServerURL="http://localhost/qgis/qgis_mapserv.fcgi.exe"
;List of URL available for the web client
onlyMaps=off
cacheStorageType=file
;cacheStorageType=sqlite => store cached images in one sqlite file per repo/project/layer
;cacheStorageType=file => store cached images in one folder per repo/project/layer. The root folder is /tmp/
cacheRedisHost=localhost
cacheRedisPort=6379
cacheExpiration=0
; default cache expiration : the default time to live of data, in seconds.
; 0 means no expiration, max : 2592000 seconds (30 days)
debugMode=0
; debug mode
; on = print debug messages in lizmap/var/log/messages.log
; off = no lizmap debug messages
cacheRootDirectory="C:\webserver\tmp"
; cache root directory where cache files will be stored
; must be writable

; path to find repositories
; rootRepositories="path"
; Does the server use relative path from root folder? 0/1
; relativeWMSPath=0

appName=Lizmap
qgisServerVersion=3.0
wmsMaxWidth=3000
wmsMaxHeight=3000
projectSwitcher=off
relativeWMSPath=0
requestProxyEnabled=0
requestProxyType=http
requestProxyNotForDomain="localhost,127.0.0.1"

[repository:demo]
label=DEMO
path="C:\webserver\www\lizmap\lizmap\install\qgis/"
allowUserDefinedThemes=1
