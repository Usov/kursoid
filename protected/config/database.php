<?php

// This is the database connection configuration.
return array(
//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
    // uncomment the following lines to use a MySQL database
//
//    'connectionString' => 'mysql:host=a0013854.xsph.ru;dbname=a0013854_kursoid',
//    'emulatePrepare' => true,
//    'username' => 'a0013854_kursoid',
//    'password' => 'qwerty123',
//    'charset' => 'utf8',
//    // включаем профайлер
//    'enableProfiling' => true,
//    // показываем значения параметров
//    'enableParamLogging' => true,

    'connectionString' => 'mysql:host=localhost;dbname=kursoid',
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => 'arahna1505',
    'charset' => 'utf8',
    // включаем профайлер
    'enableProfiling' => true,
    // показываем значения параметров
    'enableParamLogging' => true,
);