<?php

class RatesApiModel {
    private  $result = array();
    private $currency = array();

    public function __construct(){
        $this->currency = Yii::app()->params['api']['currency'];
    }

    public function add($rate){
        $key = $this->currency[$rate->currency];
        $info['sell'] = $rate->sale;
        $info['buy'] = $rate->buy;
        $info['amount'] = $rate->sum ? $rate->sum : 1;
        $this->result[$rate->bank_id][$key][] = $info;
    }

    public function getResult(){
        return $this->result;
    }

}