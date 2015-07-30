<?php

class ApiData {

    public static function getRates($checkCache=true){
        $data = array();
        if($checkCache)
            $data = Cache::get(Yii::app()->params['cache']['keys']['rates']);
        if(!$data){
            $rates = BankCourses::model()->findRatesToApi()->findAll();
            $answer = new RatesApiModel();
            foreach($rates as $rate){
                $answer->add($rate);
            }
            $data = $answer->getResult();
            Cache::set(Yii::app()->params['cache']['keys']['rates'],$data,Yii::app()->params['cache']['time']);
        }
        return $data;
    }

    public static function getDepartments($checkCache=true){
        $data = array();
        if($checkCache)
            $data = Cache::get(Yii::app()->params['cache']['keys']['departments']);

        if(!$data){
            $availableBanks = BankCourses::model()->findAvailableBanks()->findAll();

            $ids = array();
            foreach($availableBanks as $bankInfo){
                $ids[]=$bankInfo['bank_id'];
            }
            $answer = new DepartmentApiModel();
            $banks = Bank::model()->getBankToAPI($ids)->findAll();

            foreach($banks as $bank){
                $answer->setBank($bank);
                $departments = BankBranches::model()->getBankDepartment($bank->id)->findAll();
                foreach($departments as $department){
                    $answer->setDepartment($department);
                    $answer->add();
                }
            }
            $data = $answer->getResult();
            Cache::set(Yii::app()->params['cache']['keys']['departments'],$data,Yii::app()->params['cache']['time']);
        }
        return $data;
    }


    public static function getCoordinates($checkCache=true){
        $data = array();
        if($checkCache)
            $data = Cache::get(Yii::app()->params['cache']['keys']['coordinates']);
        if(!$data){
            $availableBanks = BankCourses::model()->findAvailableBanks()->findAll();

            $ids = array();
            foreach($availableBanks as $bankInfo){
                $ids[]=$bankInfo['bank_id'];
            }

            $answer = new CoordinatesApiModel();
            $coordinates = BankBranches::model()->getDepartmentCoordinates($ids)->findAll();

            foreach($coordinates as $coordinate){
                $answer->add($coordinate);
            }
            $data = $answer->getResult();
            Cache::set(Yii::app()->params['cache']['keys']['coordinates'],$data,Yii::app()->params['cache']['time']);
        }
        return $data;
    }

    public static function getCheck(){
        return array('rates'=>Cache::getCheck(Yii::app()->params['cache']['keys']['rates']),
            'departments'=>Cache::getCheck(Yii::app()->params['cache']['keys']['departments']),
            'coordinates'=>Cache::getCheck(Yii::app()->params['cache']['keys']['departments'])
        );
    }
}