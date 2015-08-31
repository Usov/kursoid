<?php

class BanksController extends Controller{

    public function actionIndex(){
        $banks = Bank::model()->getBankToAdmin()->findAll();
        $this->render('index', ['banks'=>$banks]);
    }

    public function actionView(){
        $bankId = Yii::app()->request->getQuery('id');
        $bank = Bank::model()->findByPk($bankId);
        $departments = BankBranches::model()->getBankDepartment($bankId)->findAll();
        $rates = BankCourses::model()->getBankRates($bankId)->findAll();
        $this->render('view', ['bank'=>$bank, 'departments'=>$departments, 'rates'=>$rates]);
    }

    public function actionEdit(){

    }
}