<?php

class ApiData {

    public static function getRates($checkCache=true){
        $response = new ApiResponseModel();

        $data = array();

        if($checkCache){
            $data = ApiCache::get(Yii::app()->params['cache']['keys']['rates']);
        }

        if(!$data){
            $rates = BankCourses::model()->findRatesToApi()->findAll();
            $ratesData = new RatesApiModel();
            foreach($rates as $rate){
                $ratesData->add($rate);
            }
            $response->setData($ratesData->getResult());
            $response->setHash(ApiCache::set(Yii::app()->params['cache']['keys']['rates'],
                $data, Yii::app()->params['cache']['time']));
        }
        else{
            $response->setData($data);
            $response->setHash(ApiCache::getCheck(Yii::app()->params['cache']['keys']['rates']));
        }
        return $response->getApiResponse();
    }

    public static function getDepartments($checkCache=true){
        $response = new ApiResponseModel();

        $data = array();

        if($checkCache)
            $data = ApiCache::get(Yii::app()->params['cache']['keys']['departments']);

        if(!$data){
            $availableBanks = BankCourses::model()->findAvailableBanks()->findAll();
            if(count($availableBanks)>0){
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

                $response->setHash(ApiCache::set(Yii::app()->params['cache']['keys']['departments'],
                    $data,Yii::app()->params['cache']['time']));
                $response->setData($answer->getResult());
            }

        }
        else{
            $response->setData($data);
            $response->setHash(ApiCache::getCheck(Yii::app()->params['cache']['keys']['departments']));
        }

        return $response->getApiResponse();
    }


    public static function getCoordinates($checkCache=true){
        $response = new ApiResponseModel();
        $data = array();

        if($checkCache)
            $data = ApiCache::get(Yii::app()->params['cache']['keys']['coordinates']);

        if(!$data){

            $availableBanks = BankCourses::model()->findAvailableBanks()->findAll();
            if(count($availableBanks)>0){
                $ids = array();
                foreach($availableBanks as $bankInfo){
                    $ids[]=$bankInfo['bank_id'];
                }

                $answer = new CoordinatesApiModel();
                $coordinates = BankBranches::model()->getDepartmentCoordinates($ids)->findAll();
                foreach($coordinates as $coordinate){
                    $answer->add($coordinate);
                }
                $response->setData($answer->getResult());
                $response->setHash(ApiCache::set(Yii::app()->params['cache']['keys']['coordinates'],
                    $data,Yii::app()->params['cache']['time']));
            }
        }
        else{
            $response->setData($data);
            $response->setHash(ApiCache::getCheck(Yii::app()->params['cache']['keys']['coordinates']));
        }
        return $response->getApiResponse();
    }

    public static function getCheck(){
        return array('rates'=>ApiCache::getCheck(Yii::app()->params['cache']['keys']['rates']),
            'departments'=>ApiCache::getCheck(Yii::app()->params['cache']['keys']['departments']),
            'coordinates'=>ApiCache::getCheck(Yii::app()->params['cache']['keys']['coordinates'])
        );
    }
}