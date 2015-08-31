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
        if(count($availableBank)>0)
            $this->getDbCriteria()->mergeWith(array(
                'select' => 'longtitude, latitude, id',
                'condition' => 'bank_id in ('.implode(',',$availableBank).')',
                'group'=>'longtitude, latitude, bank_id'
            ));
        return $this;
    }

    public function preparePhone($phone){

        $phoneWithoutChars = preg_replace('/[^0-9]/', '', $phone);
        $newPhone = '';
        if(substr($phoneWithoutChars, 0, 1)== '8' ){
            $newPhone = substr($phoneWithoutChars, 1, 10);
        }
        elseif(substr($phoneWithoutChars, 0, 1)== '+' ){
            $newPhone = substr($phoneWithoutChars, 2, 10);
        }
        else{
            $newPhone = substr($phoneWithoutChars, 0, 10);
        }
        if($newPhone)
            $this->phone = '+7'.$newPhone;
        return $this->phone;
    }


}



