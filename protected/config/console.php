<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Pozdrav-s Console Application',

    // preloading 'log' component
    'preload'=>array('log'),


    'import'=>array(
        'application.models.*',
        'application.models.api.*',
        'application.components.*',
        'application.components.parser.*'
    ),
    // application components
    'components'=>array(

        // database settings are configured in database.php
        'db' => require(dirname(__FILE__) . '/database.php'),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
        'cache'=>array(
            'class'=>'system.caching.CMemCache',
            'servers'=>array(
                array('host'=>'localhost', 'port'=>11211),
            ),
        ),
    ),

    'params'=>array(
        'eur'=>2,
        'usd'=>1,
        'sourceId' => array(
            'rbc' => 1,
            'bankiRu' => 2,
            'exocur' => 3
        ),
        'sourceCurrency'=>array(
            1=>array(
                '1'=>'3', // USD
                '2'=>'2' // EUR
            ),
            2=>array(
                '1'=>'usd',
                '2'=>'eur'
            )
        ),
        'cache'=>array(
            'on'=>false,
            'keys'=>array(
                'rates'=>'rates_key',
                'departments'=>'departments_key',
                'coordinates'=>'coordinates_key'
            ),
            'time'=>60*60
        )

    )
);
