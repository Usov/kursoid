<?php

class BanksController extends Controller{

    public function actionIndex(){
        $banks = Bank::model()->with('departmentsCount', 'rates')->getBankToAdmin()->findAll();
        $this->render('index', array('banks'=>$banks));
    }

    public function actionView(){
        $bankId = Yii::app()->request->getQuery('id');
        $bank = Bank::model()->with('departments', 'rates')->findByPk($bankId);
        $this->render('view', array('bank'=>$bank));
    }

    public function actionSave(){
        $status = 0;
        $bank = null;

        $bankInfo = Yii::app()->request->getQuery('bank');
        if($bankInfo['id'])
            $bank = Bank::model()->findByPk($bankInfo['id']);

        if(!$bank)
            $bank = new Bank();

        $bank->attributes = $bankInfo;
        if($bank->validate()){
            $status = $bank->save();
        }

        $this->renderJSON(array('status'=>$status));
    }

    public function actionDelete(){
        $status = 0;
        $bankId = Yii::app()->request->getQuery('id');
        $bank = Bank::model()->findByPk($bankId);
        if($bank){
            $bank->setDelete();
            $status = $bank->save();
        }
        $this->renderJSON(array('status'=>$status));
    }

    public function actionList(){

        $banks = Bank::model()->getBankToAdmin()->findAll();
        $this->renderJSON($banks);
    }

    public function actionAccept(){
        $status = 0;
        $bankId = Yii::app()->request->getQuery('id');
        $accept = Yii::app()->request->getQuery('accept');
        $bank = Bank::model()->findByPk($bankId);
        if($bank){
            $bank->setStatus($accept);
            $status = $bank->save();
        }
        $this->renderJSON(array('status'=>$status));
    }
}