<?php

class RatesControllers extends Controller{

    public function actionSave(){
        $resultStatus = 0;
        $rate = null;

        $rateInfo = Yii::app()->request->getQuery('rate');
        if($rateInfo){
            if($rateInfo['id'])
                $rate = BankCourses::model()->findByPk($rateInfo['id']);

            if(!$rate)
                $rate = new BankCourses();

            $rate->attributes =$rateInfo;
            if($rate->validate()){
                $resultStatus = $rate->save();
            }
        }
        $this->renderJSON(array('status'=>$resultStatus));
    }
}