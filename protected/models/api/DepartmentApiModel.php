<?php

class DepartmentApiModel {
    private  $result = array();
    private  $department;
    private  $bank;

    public function setBank($bank){
        $this->bank = $bank;
    }

    public function setDepartment($department){
        $this->department = $department;
    }

    public function add(){
        $info = array();
        $info['name'] = $this->bank->name;
        $info['phone'] = $this->bank->phone;
        $info['address'] = $this->department->address;
        $info['bankId'] = $this->bank->id;
        $this->result[$this->department->id] = $info;
    }

    public function getResult(){
        return $this->result;
    }

}