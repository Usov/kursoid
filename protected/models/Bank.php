<?php

class Bank extends CActiveRecord{
    public $id;
    public $name;
    public $phone;
    public $source_id;
    public $source_alias;



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
        $cut_name = preg_replace('/(банк|Банк|Россия|Российский|российский|России|россии|\(.*\))/','',$name);
        $cut_name = trim($cut_name, '-');
        $cut_name = trim($cut_name);
        $cut_name=$name;
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'name like "%'.$cut_name.'%"',
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

    public function getBankToAdmin()
    {
        return $this;
    }


    public function getBankResource($source_id, $source_alias)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'source_id=:sd and source_alias=:sa',
            'params'=>array(':sd'=>$source_id,':sa'=>$source_alias)
        ));
        return $this;
    }


}


