<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&( 
filemtime('C:\webserver\www\lizmap36\lizmap/app/system/urls.xml') > 1696403627 || filemtime('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib/jelix/core-modules/jelix\urls.xml') > 1696258090
)) { return false; } else {
$GLOBALS['SIGNIFICANT_CREATEURL'] =array (
  'view~default:index@classic' => 
  array (
    0 => 1,
    1 => 'index',
    2 => false,
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => '',
    6 => false,
    7 => 
    array (
    ),
  ),
  'jelix~error:notfound@classic' => 
  array (
    0 => 1,
    1 => 'index',
    2 => false,
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => '/jelix/404.html',
    6 => false,
    7 => 
    array (
    ),
  ),
  'jelix~error:badright@classic' => 
  array (
    0 => 1,
    1 => 'index',
    2 => false,
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => '/jelix/403.html',
    6 => false,
    7 => 
    array (
    ),
  ),
  'jelix~jforms:getListData@classic' => 
  array (
    0 => 1,
    1 => 'index',
    2 => false,
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => '/jelix/forms/getdata',
    6 => false,
    7 => 
    array (
    ),
  ),
  'jelix~jforms:js@classic' => 
  array (
    0 => 1,
    1 => 'index',
    2 => false,
    3 => 
    array (
      0 => '__form',
      1 => '__fid',
    ),
    4 => 
    array (
      0 => 0,
      1 => 0,
    ),
    5 => '/jelix/forms/js/:__form/:__fid.js',
    6 => false,
    7 => 
    array (
    ),
  ),
  'jelix~www:getfile@classic' => 
  array (
    0 => 1,
    1 => 'index',
    2 => false,
    3 => 
    array (
      0 => 'targetmodule',
      1 => 'file',
    ),
    4 => 
    array (
      1 => 1,
      0 => 0,
    ),
    5 => '/jelix/res/:targetmodule/:file',
    6 => false,
    7 => 
    array (
    ),
  ),
  'master_admin~default:index@classic' => 
  array (
    0 => 1,
    1 => 'admin',
    2 => false,
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => '',
    6 => false,
    7 => 
    array (
    ),
  ),
  '@classic' => 
  array (
    0 => 2,
    1 => 'index',
    2 => false,
  ),
  '@cmdline' => 
  array (
    0 => 2,
    1 => 'cmdline',
    2 => false,
  ),
  'lizmap~*@classic' => 
  array (
    0 => 3,
    1 => 'index',
    2 => false,
    3 => false,
    4 => '/lizmap',
  ),
  'view~*@classic' => 
  array (
    0 => 3,
    1 => 'index',
    2 => false,
    3 => false,
    4 => '/view',
  ),
  'filter~*@classic' => 
  array (
    0 => 3,
    1 => 'index',
    2 => false,
    3 => false,
    4 => '',
  ),
  'action~*@classic' => 
  array (
    0 => 3,
    1 => 'index',
    2 => false,
    3 => false,
    4 => '',
  ),
  'dataviz~*@classic' => 
  array (
    0 => 3,
    1 => 'index',
    2 => false,
    3 => false,
    4 => '',
  ),
  'dynamicLayers~*@classic' => 
  array (
    0 => 3,
    1 => 'index',
    2 => false,
    3 => false,
    4 => '/dynamic-layers',
  ),
  'admin~*@classic' => 
  array (
    0 => 3,
    1 => 'admin',
    2 => false,
    3 => false,
    4 => '',
  ),
  'jauthdb_admin~*@classic' => 
  array (
    0 => 3,
    1 => 'admin',
    2 => false,
    3 => false,
    4 => '/users-admin',
  ),
  'jacl2db_admin~*@classic' => 
  array (
    0 => 3,
    1 => 'admin',
    2 => false,
    3 => false,
    4 => '/acl-admin',
  ),
  'jcommunity~*@classic' => 
  array (
    0 => 3,
    1 => 'admin',
    2 => false,
    3 => false,
    4 => '/auth',
  ),
);
return true;
}
