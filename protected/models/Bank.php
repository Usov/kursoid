<?php

class Bank extends CActiveRecord
{
    public $id;
    public $name;
    public $address;
    public $phone;

    public function tableName()
    {
        return 'site_bank';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getBankByNameLike($name)
    {
        $cut_name = preg_replace('/(банк|Банк|Россия|Российский|российский|\(.*\))/','',$name);
        $cut_name = trim($cut_name, '-');
        $cut_name = trim($cut_name);
        print $cut_name.PHP_EOL;
//        $cut_name = '1Банк — Московский филиал';
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'name like "%'.$cut_name.'%"',
//            'params'=>array(':name'=>$cut_name)
        ));
        return $this;
    }

    public function getBankToAPI($ids)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'id in ('.implode(',',$ids).')'
        ));
        return $this;
    }
}