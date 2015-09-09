<?php

define('BANK_STATUS_ON', 0);
define('BANK_STATUS_MODERATE', 1);
define('BANK_STATUS_OFF', 2);
define('BANK_IS_DELETE', 1);

class Bank extends CActiveRecord{

    public $id;
    public $name;
    public $phone;
    public $source_id;
    public $source_alias;
    public $status=0;
    public $is_delete=0;


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
            'condition'=>'id in ('.implode(',',$ids).') and status='.BANK_STATUS_ON.' and is_delete!='.BANK_IS_DELETE,
        ));
        return $this;
    }

    public function getBankToAdmin()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'is_delete!='.BANK_IS_DELETE,
        ));
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

    public function relations()
    {
        return array(
            'departments' => array(self::HAS_MANY, 'BankBranches', array('bank_id'=>'id')),
            'departmentsCount' => array(self::STAT, 'BankBranches', 'bank_id'),
            'rates' => array(self::HAS_MANY, 'BankCourses', array('bank_id', 'id'))
        );
    }

    public function setDelete(){
        $this->is_delete = BANK_IS_DELETE;
    }

    public function setStatus($status){
        switch($status){
            case BANK_STATUS_OFF:
            case BANK_STATUS_ON:
                $this->is_delete = $status;
                break;
        }
    }

}


