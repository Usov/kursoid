<?php

class BanksController extends Controller{

    public function actionIndex(){
        $banks = ParseBank::model()->getBankToAdmin()->findAll();
        $this->render('index', ['banks'=>$banks]);
    }

    public function actionView(){
        $bankId = Yii::app()->request->getQuery('id');
        $bank = ParseBank::model()->findByPk($bankId);
        $departments = ParseBankBranches::model()->getBankDepartment($bankId)->findAll();
        $rates = BankCourses::model()->getBankRates($bankId)->findAll();
        $this->render('view', ['bank'=>$bank, 'departments'=>$departments, 'rates'=>$rates]);
    }

    public function actionEdit(){

    }
}