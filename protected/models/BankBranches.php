<?php

class BankBranches extends CActiveRecord
{
    public $id;
    public $longtitude;
    public $latitude;
    public $address;
    public $bank_id;
    public $phone;

    public function tableName()
    {
        return 'site_bank_branches';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getBankDepartment($bankId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'bank_id=:bi',
            'params'=>array(':bi'=>$bankId)
        ));
        return $this;
    }

    public function getDepartmentCoordinates($availableBank)
    {
        $this->getDbCriteria()->mergeWith(array(
            'select' => 'longtitude, latitude, id',
            'condition' => 'bank_id in ('.implode(',',$availableBank).')',
            'group'=>'longtitude, latitude, bank_id'
        ));
        return $this;
    }


}