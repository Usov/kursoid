<?php

class RbcParser extends Parser {

    protected $siteCurrencyType = 0;
    protected $currencyType = 0;


    public function __set($key, $val){
        if($key=='currencyType'){
            $this->siteCurrencyType = $val;
            $val = Yii::app()->params['sourceCurrency'][$this->sourceType][$this->siteCurrencyType];

        }

        $this->$key = $val;
    }

    public function parse(){

        libxml_use_internal_errors(true);
//
//        $opts = array(
//            'http'=>array(
//                'charset'=>"utf-8",
//            )
//        );

//        $context = stream_context_create($opts);

        $html = file_get_contents(DownloadUrlCreator::rbc($this->currencyType));
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="UTF-8">' .$html);
        $table = $dom->getElementById('tableBody');
        $row = $table->getElementsByTagName('tr');
        foreach($row as $currentRow){
            $columns = $currentRow->getElementsByTagName('td');
            $bank = array();
            $bankId = 0;
            foreach($columns as $column){
                $class = $column->getAttribute('class');
                $bank[$class] = $column->nodeValue;

                switch($class){
                    case 'name':
                        $href = $column->getElementsByTagName('a')->item(0)->getAttribute('href');
                        $bankId = preg_replace("/[^0-9]/", '', $href);
                        if(!in_array($bankId, $this->banksId))
                            $this->banksId[] = $bankId;
                        break;

                    case 'info':
                        $bank['info'] = array();
                        $span = $column->getElementsByTagName('span');
                        foreach($span as $info){
                            $bank['info'][$info->getAttribute('id')] = $info->nodeValue;
                        }

                    default:
                        break;
                }
            }
            $this->banks[$bankId][$bank['sum']] = $bank;
        }
    }

    public function save(){
        foreach($this->banks as $key=>$bankInfo){
            $bank = False;

            $bankResource = BankResources::model()->getBankResource($this->sourceType, $key)->find();
            if($bankResource){
                $bank = Bank::model()->findByPk($bankResource->bank_id);
            }
            if(!$bank)
                $bank = new Bank();
            foreach($bankInfo as $sum=>$info){
                if(!$bank->id){
                    $bank->phone = $info['info']['tel'];
                    $bank->address =  $info['info']['address'];
                    $bank->name = $info['name'];
                    $bank->save();

                    $bankResource = new BankResources();
                    $bankResource->bank_id = $bank->id;
                    $bankResource->source_id = $this->sourceType;
                    $bankResource->source_alias = $key;
                    $bankResource->save();

                    $newBranch = new BankBranches();
                    $newBranch->address =  $info['info']['address'];

                    $yandexGeoDecode = @file_get_contents('http://geocode-maps.yandex.ru/1.x/?format=json&results=1&geocode=город Москва, '. $newBranch->address);
                    if($yandexGeoDecode){
                        $answer = json_decode($yandexGeoDecode,True);
                        if(!isset($answer['response']) && count($answer['response']['GeoObjectCollection']['featureMember']) == 0){
                            continue;
                        }
                        $pos = $answer['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
                        $pos = explode(' ',$pos);
                        $newBranch->latitude = $pos[0];
                        $newBranch->longtitude = $pos[1];
                        $newBranch->save();
                        $newBranch->bank_id = $bank->id;
                        $newBranch->save();
                    }
                }

                if($bank->id){
                    $cur = new BankCourses();
                    $cur->bank_id = $bank->id;
                    $cur->buy = $info['pok'];
                    $cur->sale = $info['prod'];
                    $cur->sum = $sum;
                    $cur->currency = $this->siteCurrencyType;
                    $cur->save();
                }

            }
        }
    }
}

//        foreach($this->banksId as $id){
//            $html = file_get_contents('http://quote.rbc.ru/cash/bank/'.$id.'.html');
//            $dom = new DOMDocument();
//            $dom->loadHTML($html);
//            $td = $dom->getElementsByTagName('td');
//            foreach($td as $element){
//                if($element->getAttribute('class') == 'left_column'){
//                    $leftColumn = $element;
//                    break;
//                }
//            }
//
//            $info = array();
//            if($leftColumn){
//                $p = $leftColumn->getElementsByTagName('p');
//                foreach($p as $bankInfo){
//
//                    if(strpos($bankInfo->nodeValue, 'наименование')!==false){
//                        $info['fullName'] = $bankInfo->getElementsByTagName('strong')->item(0)->nodeValue;
//                        continue;
//                    }
//
//                    if(strpos($bankInfo->nodeValue, 'Телефон')!==false){
//                        $info['phone'] = $bankInfo->getElementsByTagName('strong')->item(0)->nodeValue;
//                        continue;
//                    }
//
//                    if(strpos($bankInfo->nodeValue, 'Адрес')!==false){
//                        $info['address'] = $bankInfo->getElementsByTagName('strong')->item(0)->nodeValue;
//                        continue;
//                    }
//                }
//
//                $this->banks[$id]['info'] = $info;
//            }
//
//        }
//        print_r($this->banks);
