<?php

// This is the database connection configuration.
return array(
//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
    // uncomment the following lines to use a MySQL database
//
    'connectionString' => 'mysql:host=okruble.mysql;dbname=okruble_db',
    'emulatePrepare' => true,
    'username' => 'okruble_mysql',
    'password' => 'E:oUC2aF',
    'charset' => 'utf8',
    // включаем профайлер
    'enableProfiling' => true,
    // показываем значения параметров
    'enableParamLogging' => true,
//
//    'connectionString' => 'mysql:host=localhost;dbname=kursoid',
//    'emulatePrepare' => true,
//    'username' => 'okruble_db',
//    'password' => 'arahna1505',
//    'charset' => 'utf8',
//    // включаем профайлер
//    'enableProfiling' => true,
//    // показываем значения параметров
//    'enableParamLogging' => true,
);