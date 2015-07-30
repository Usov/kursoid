<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'kursoid',

    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.api.*',
        'application.components.*',
    ),

    'modules' => array(
        // uncomment the following to enable the Gii tool
        'admin'=>array(
//
        ),

        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'1',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
        ),

    ),

    // application components
    'components' => array(

//        'user' => array(
//            // enable cookie-based authentication
//            'allowAutoLogin' => true,
//        ),

        // uncomment the following to enable URLs in path-format
//        'cache'=>array(
//            'class'=>'system.caching.CMemCache',
//            'servers'=>array(
//                array('host'=>'localhost', 'port'=>11211)
//            ),
//        ),
        'urlManager' => array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules' => array(
                array(
                    'class' => 'application.components.PageUrlRule',
                    'connectionID' => 'db',
                ),
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'cache'=>array(
            'class'=>'system.caching.CMemCache',
            'servers'=>array(
                array('host'=>'localhost', 'port'=>11211),
            ),
        ),

//        'mcache' => array(
//            'class' => 'CMemCache',
//            'servers' => array(
//                array(
//                    'host' => '127.0.0.1',
//                    'port' => 11211,
//                ),
//            ),
//        ),
        // database settings are configured in database.php
        'db' => require(dirname(__FILE__) . '/database.php'),
        'authManager'=>array(
            'class'=>'CPhpAuthManager',
            'defaultRoles'=>array('authenticated'),

        ),

        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),

        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    // направляем результаты профайлинга в ProfileLogRoute (отображается
                    // внизу страницы)
                    'class' => 'CProfileLogRoute',
                    'levels' => 'profile',
                    'enabled' => true,
                ),
//                 uncomment the following to show log messages on web pages

                array(
                    'class' => 'CWebLogRoute',
                ),

            ),
        ),

    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'api'=>array(
            'currency'=> array(
                1=>'dollar',
                2=>'euro'
            )
        ),
        'cache'=>array(
            'on'=>true,
            'keys'=>array(
                'rates'=>'rates_key',
                'departments'=>'departments_key',
                'coordinates'=>'coordinates_key'
            ),
            'time'=>60*60
        )

    ),

);
