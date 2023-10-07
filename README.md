# Easy install/deploy QGIS Server and Lizmap Web Client on MS Windows

Credits: João Gaspar, https://github.com/jonnyforestGIS

Last Tested on: QGIS Server 3.28.11 LTR + PHP 8.2 + LizMap Web Client 3.6.6

:warning:**Warning for Portuguese Users**:warning:: If you use the application for Cartão de Cidadão Autenticação GOV, we found a issue that breaks the QGIS and QGIS Server instalation. Please check https://github.com/amagovpt/autenticacao.gov/issues/88

**The following guide assume that the user will keep the suggested installation/deploy paths**. If there is the necessity to use custom installatin/deploy paths then a number of configuration files (Apache, PHP) will need to be modified. **This guide also assumes that on the Windows machine being used there aren't any other programs/services running on port 80**.

1) Download the OSGeo4W installer: https://download.osgeo.org/osgeo4w/v2/osgeo4w-setup.exe 

2) Use the "advanced" install method. Install the packages "qgis-ltr", "qgis-ltr-server" and "fcgi", let the installer manage the installation of dependencies. Use the default installation path:

```
C:\OSGeo4W
```

![osgeo1](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/33ce533e-cd3e-4caa-86fd-50eec42a5e92)

3) Download the file https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/archive/refs/heads/master.zip unzip it and copy the "webserver" folder inside the C: drive

![image](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/2a7a494e-23ea-472c-9f50-78643f477545)

4) Start the Apache web server by running **as administrator** (right click > run as administrator) the file 

```
C:\webserver\Apache24\bin\httpd.exe
```
At this point you could see this message from Windows firewall: allow Apache to serve pages through it.

![windows_firwall](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/f42e0a44-cf9a-4553-9205-d7bb82c41d44)

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

![image](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/82795341-7906-4f9b-a326-09d1408d44f2)

7) Test if PHP works:

```
http://localhost/info.php
```

![image](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/cd893de8-0bc3-4b8a-8f4c-2d35fffd7034)

8) Test if QGIS Server works:

```
http://localhost/qgis/qgis_mapserv.fcgi.exe
```

![image](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/d6ced0e0-ae6c-4402-95d0-1565fe0eccbd)

9) Test if Lizmap Web Client works:

```
http://localhost/lizmap/lizmap/www/index.php
```

![image](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/611b3119-69ec-4399-aa09-49dfcbb830c5)

```
http://localhost/lizmap36/lizmap/www/index.php/view/map?repository=demos&project=montpellier
```

![image](https://github.com/NaturalGIS/qgis_server_and_lizmap_on_windows/assets/1951107/07bf8166-4377-4d0c-bc63-c56674991511)


10) On a completely clean Window 10/11 machine you can get an error message about missing some library. If is the case just download and install them from the MS web site: https://www.microsoft.com/en-us/download/details.aspx?id=52685