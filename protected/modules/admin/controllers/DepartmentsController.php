<?php

class DepartmentsController extends Controller{

    public function actionSave(){
        $resultStatus = 0;
        $department = null;

        $departmentInfo = Yii::app()->request->getQuery('department');
        if($departmentInfo){
            if($departmentInfo['id'])
                $department = BankBranches::model()->findByPk($departmentInfo['id']);

            if(!$department)
                $bank = new Bank();

            $bank->attributes = $departmentInfo;
            if($bank->validate()){
                $resultStatus = $bank->save();
            }
        }
        $this->renderJSON(array('status'=>$resultStatus));
    }

    public function actionDelete(){
        $resultStatus = 0;
        $departmentId = Yii::app()->request->getQuery('id');
        if($departmentId){
            $department = BankBranches::model()->findByPk($departmentId);
            if($department){
                $department->setDelete();
            }
            $resultStatus = $department->save();
        }
        $this->renderJSON(array('status'=>$resultStatus));
    }

}