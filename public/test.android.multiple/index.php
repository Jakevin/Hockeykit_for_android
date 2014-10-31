<?php

require_once('../../public/config.php');
require('../../includes/main.php');

$router = Router::get(array('appDirectory' => dirname(__FILE__).DIRECTORY_SEPARATOR));
$apps = $router->app;
$b = $router->baseURL;
DeviceDetector::detect();

$pre_app = $apps->applications;

static $app;
foreach ($pre_app as $apk) {
    $app = $apk;
}

usort($pre_app, function($currentAry, $nextAry) {
    return -strnatcmp($currentAry["path"], $nextAry["path"]);
});

$path = $_SERVER[PHP_SELF];

$path_parts = pathinfo($path);  //這邊的$path_parts會是一個陣列，裡面的元素就是我們要的資訊。

$app_png = glob("./*.png");

$allapk = list_apk($path_parts['dirname']);

function list_apk($dir){
    static $allapk = array();
    $dirs = glob("./*",GLOB_ONLYDIR);

    foreach ($dirs as $d) {
        if(count(glob($d.'/*.apk')) > 0 ){
            foreach (glob($d.'/*.apk') as $apk) {
                array_push($allapk, $apk);
            }
        }
    }
    return $allapk;
}




?>
<html>
<head><title></title>
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="<?php echo $b ?>../blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="<?php echo $b ?>../blueprint/print.css" type="text/css" media="print">
    <!--[if IE]><link rel="stylesheet" href="<?php echo $b ?>blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
    <link rel="stylesheet" href="<?php echo $b ?>../blueprint/plugins/buttons/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" type="text/css" href="<?php echo $b ?>../css/stylesheet.css">
</head>
<body>


    <h1>List <?php echo $app[AppUpdater::INDEX_APP] ?> all version</h1>

    <img class="icon" src="<?php echo $b.substr($app_png[0],1) ?>">

    <p></p>

        <table  Border="10" CellSpacing="10" CellPadding="10">
        <?php 



            foreach ($pre_app as $apk) {
                echo '<tr><td><div class="column span-6">';
                echo '<b>Size:</b>' . round($apk[AppUpdater::INDEX_APPSIZE] / 1024 / 1024, 1) . " MB<br/>";
                echo '<a  class="button" href="'.$apk["path"].'"> Download '.$apk[AppUpdater::INDEX_SUBTITLE] . '(' . $apk[AppUpdater::INDEX_VERSION] . ')</a></br></div>';
                echo '</td></tr>';
            }
        ?>
    </table>
</body>
</html>