<?php
class ParseBank extends Bank
{

    public $source_id;
    public $source_alias;

    public function tableName()
    {
        return 'parse_bank';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
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