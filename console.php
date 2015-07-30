<?php
//defined('YII_DEBUG') or define('YII_DEBUG',false);
require_once(dirname(__FILE__).'/framework/yii.php');
$config=dirname(__FILE__).'/protected/config/console.php';
Yii::createConsoleApplication($config)->run();