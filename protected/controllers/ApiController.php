<?php

class ApiController extends Controller {

    public function actionIndex(){
        print 'ok';
    }

    public function actionGetDepartments(){
        $data = ApiData::getDepartments();
        $this->renderPartial('index', array('answer'=>json_encode($data)));

    }

    public function actionGetRates(){
        $data = ApiData::getRates();
        $this->renderPartial('index', array('answer'=>json_encode($data)));

    }

    public function actionGetCoordinates(){
        $data = ApiData::getCoordinates();
        $this->renderPartial('index', array('answer'=>json_encode($data)));
    }

    public function actionCheckData(){
        $data = ApiData::getCheck();
        $this->renderPartial('index', array('answer'=>json_encode($data)));
    }

}