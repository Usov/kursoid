<?php

class BankCourses extends CActiveRecord{
    public $id;
    public $bank_id;
    public $sum;
    public $currency;
    public $buy;
    public $sale;


    public function tableName()
    {
        return 'site_bank_courses';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function findRatesToApi()
    {
        $this->getDbCriteria()->mergeWith(array(
            'order'=>'sum'
        ));
        return $this;
    }

    public function findAvailableBanks(){
        $this->getDbCriteria()->mergeWith(array(
            'select' => 'bank_id',
            'group'=>'bank_id'
        ));
        return $this;
    }



}