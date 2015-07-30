<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alekseenko
 * Date: 7/28/15
 * Time: 4:48 PM
 * To change this template use File | Settings | File Templates.
 */

class ApiCache {
    public static function set($key, $data, $time){
        $hash = '';
        if(Yii::app()->params['cache']['on']==true){
            Yii::app()->cache->set($key,json_encode($data),$time);
            $hash = md5(json_encode($data));
            Yii::app()->cache->set($key.'_check',$hash,$time);
        }
        return $hash;
    }

    public static function get($key){
        if(Yii::app()->params['cache']['on']==true)
            return json_decode(Yii::app()->cache->get($key));
    }

    public static function getCheck($key){
        if(Yii::app()->params['cache']['on']==true)
            return Yii::app()->cache->get($key.'_check');
    }
}