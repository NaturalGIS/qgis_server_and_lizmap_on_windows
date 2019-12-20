# Easy install/deploy QGIS Server and Lizmap Web Client on MS Windows

Credits: João Gaspar, https://github.com/jonnyforestGIS

The following steps assume that the user will keep the suggested installation/deploy paths. If there is the necessity to use custom installatin/deploy paths then a number of configuration files (Apache, PHP) will need to be modified. This guide also assumes that on the Windows machine being used there aren't any other programs/services running on port 80.

1) Download the OSGeo4W 64 bit installer: http://download.osgeo.org/osgeo4w/osgeo4w-setup-x86_64.exe

2) Install the packages "qgis-ltr", "qgis-server-ltr", "gdal204dll" and "fcgi", let the installer manage the installation of dependencies

3) Copy the "webserver" folder inside the C: drive

4) Start the Apache web server by double clicking (as administrator) the file *C:\webserver\Apache24\bin\httpd.exe*

5) As option Apache can be installed as Windows service by running (from the Windows console, launched as administrator) the following command:

C:\webserver\Apache24\bin\httpd.exe -k install

After that Apache can be started/stopped from the Windows Services control panel

6) Open a browser and test if Apache works:

http://localhost

7) Test if PHP works:

http://localhost/info.php

8) Test if QGIS Server works:

http://localhost/qgis/qgis_mapserv.fcgi.exe

9) Using a  Windows console, launched as administrator, change the location inside the Lizmap Web Client folder

cd C:\webserver\www\lizmap

then run the following command

C:\webserver\php73\php.exe lizmap/install/installer.php

10) Test if Lizmap Web Client works:

http://localhost/lizmap/lizmap/www/index.php
