<?php

class CoordinatesApiModel {
    private $result = array();
    private $currency = array();

    public function __construct(){
        $this->currency = Yii::app()->params['api']['currency'];
    }

    public function add($coordinate){
        $this->result[$coordinate->id] = array( $coordinate->latitude, $coordinate->longtitude);
    }

    public function getResult(){
        return $this->result;
    }

}