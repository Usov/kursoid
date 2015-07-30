<?php

abstract class Parser {


    protected $sourceType = '';
    protected $banks = array();
    protected $banksId = array();

    abstract public function parse();
    abstract public function save();


    public function  __construct($parseType){
        $this->sourceType = Yii::app()->params['sourceId'][$parseType];
    }


}