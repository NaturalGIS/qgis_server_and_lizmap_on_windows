# Easy install/deploy QGIS Server and Lizmap Web Client on MS Windows

Credits: João Gaspar, https://github.com/jonnyforestGIS

Last Tested on: QGIS Server 3.22.6 LTR + LizMap Web Client 3.4.11


The following steps assume that the user will keep the suggested installation/deploy paths. If there is the necessity to use custom installatin/deploy paths then a number of configuration files (Apache, PHP) will need to be modified. This guide also assumes that on the Windows machine being used there aren't any other programs/services running on port 80.

1) Download the OSGeo4W installer: https://download.osgeo.org/osgeo4w/v2/osgeo4w-setup.exe 

2) Install the packages "qgis-ltr", "qgis-ltr-server" and "fcgi", let the installer manage the installation of dependencies

3) Copy the "webserver" folder inside the C: drive

4) Start the Apache web server by running **as administrator** (right click > run as administrator) the file *C:\webserver\Apache24\bin\httpd.exe*

5) As option Apache can be installed as Windows service by running (from the Windows console, launched as administrator) the following command:

```
C:\webserver\Apache24\bin\install-server.bat
```

After that Apache can be started/stopped from the Windows Services control panel or from the Windows console using the command (as administrator):

```
C:\webserver\Apache24\bin\start-server.bat
```

6) Open a browser and test if Apache works:

```
http://localhost
```

7) Test if PHP works:

```
http://localhost/info.php
```

8) Test if QGIS Server works:

```
http://localhost/qgis/qgis_mapserv.fcgi.exe
```

9) Test if Lizmap Web Client works:

```
http://localhost/lizmap/lizmap/www/index.php
```

10) On a completely clean Window 10 machine you can get an error message about missing some library. If is the case just download and install them from the MS web site: https://www.microsoft.com/en-us/download/details.aspx?id=52685
