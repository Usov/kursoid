<?php

class ApiResponseModel {
    private $data;
    private $hash;

    public function setData($data){
        $this->data = $data;
    }

    public function setHash($hash){
        $this->hash = $hash;
    }

    public function getApiResponse(){
        return array('data'=>$this->data, 'hash'=>$this->hash);;
    }
}