<?php

class BankiRuParser extends Parser {

    private $alias = 'bankiRu';
    private $moscow = 4;

    public function parse(){

        libxml_use_internal_errors(true);
        $opts = array(
            'http'=>array(
                'charset'=>"utf-8",
            )
        );

        $context = stream_context_create($opts);

        $html = file_get_contents(DownloadUrlCreator::bankiRu(),
            false,
            $context);

        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $tables = $dom->getElementsByTagName('table');
        foreach($tables as $table){
            if($table->getAttribute('class') == 'standard-table standard-table--row-highlight'){
                $rateTable = $table;
            }
        }

        if($rateTable){
            $trTag = $rateTable->getElementsByTagName('tr');
            foreach($trTag as $tr){
                if($bankId = $tr->getAttribute('data-currencies-row')){
                    $tdTag = $tr->getElementsByTagName('td');
                    foreach($tdTag as $td){
                        if($aTag = $td->getElementsByTagName('a')){
                            foreach($aTag as $a){
                                if($a->getAttribute('data-currencies-bank-name')==""){
                                    $this->banks[$bankId]['href'] = $a->getAttribute('href');
                                    $this->banks[$bankId]['name'] = $a->nodeValue;
                                    continue;
                                }
                            }
                        }
                        if($currency = $td->getAttribute('data-currencies-code')){

                            $siteCurrency = array_search($currency, Yii::app()->params['sourceCurrency'][$this->sourceType]);
                            if($sum = $td->getAttribute('data-currencies-rate-buy')){
                                $this->banks[$bankId]['rates'][$siteCurrency]['buy'] = $sum;
                            }
                            elseif($sum = $td->getAttribute('data-currencies-rate-sell')){
                                $this->banks[$bankId]['rates'][$siteCurrency]['sell'] = $sum;
                            }
                        }
                    }
                }
            }
        }
    }

    public function save(){
        foreach($this->banks as $site_bank_id=>$bankInfo){
            $bank = Bank::model()->getBankByNameLike($bankInfo['name'])->find();
            if($bank){
                foreach($bankInfo['rates'] as $currency=>$info){
                    $cur = new BankCourses();
                    $cur->bank_id = $bank->id;
                    $cur->buy = $info['buy'];
                    $cur->sale = $info['sell'];
                    $cur->currency = $currency;
                    $cur->save();

                }
            }
        }
    }

    private function addBank($site_id, $bankInfo){

            $html = file_get_contents('http://www.banki.ru'.$bankInfo['href']);
            $dom = new DOMDocument();
            $dom->loadHTML($html);
            $dl = $dom->getElementsByTagName('dl');
            foreach($dl as $d){
                if($d->getAttribute('class') == 'definition-list'){
                    $bankInfoElement = $d;
                }
            }
            if($bankInfoElement){
                $info = $bankInfoElement->getElementsByTagName('dd');
                $title = $bankInfoElement->getElementsByTagName('dt');

                foreach($info as $key=>$val){
                    $t = trim($title->item($key)->nodeValue);
                    $t = trim($title->item($key)->nodeValue, ':');
                    switch($t){
                        case 'Телефоны':
                            $div = $info->item(2)->getElementsByTagName('div');
                            $phones = array();
                            foreach($div as $div){
                                $phones[]= trim($div->getElementsByTagName('span')->item(0)->nodeValue);
                            }
                            $bankInfo['phone'] = implode(',', $phones);
                            break;
                        case 'Отделения и банкоматы':
                            if($aTag = $val->getElementsByTagName('a')){
                                foreach($aTag as $a){
                                    $branchHref = $a->getAttribute('href');
                                    $html = file_get_contents('http://www.banki.ru'.$branchHref);
                                    $dom = new DOMDocument();
                                    $dom->loadHTML($html);
                                    $dl = $dom->getElementById('bank-branch');
                                    if(!$dl){
                                        $html = file_get_contents('http://www.banki.ru'.$branchHref);
                                        $dom = new DOMDocument();
                                        $dom->loadHTML($html);
                                        $dl = $dom->getElementById('bank-branch');
                                    }
                                    $bankId = $dl->getAttribute('data-bank-id');
                                }
                            }
                            break;
                        default:
                            break;
                    }
                }
                $bank = new Bank();
                $bank->name = $bankInfo['name'];
                $bank->phone = isset($bankInfo['phone'])?$bankInfo['phone']:'';
                $bank->save();

                $bankResource = new BankResources();
                $bankResource->bank_id = $bank->id;
                $bankResource->source_id = $this->sourceType;
                $bankResource->source_alias = $site_id;
                $bankResource->save();
                $this->banksId[$bankId] = $bank->id;

            }
        return $bank;
    }

    private function branchInfoLoad(){
        $banksid = array_keys($this->banksId);
        if(count($banksid)>0){
            $url = 'http://www.banki.ru/api/';
            $data = array('id'=>'2',
                'method'=>'bankGeo/getObjectsByFilter',
                'jsonrpc'=>'2.0',
                'params' =>array(
                    'bank_id'=>$banksid,
                    'limit'=>1000000,
                    'region_id'=>array(4),
                    'type'=>array('office', 'branch', 'cash'),
                    'with_empty_coordinates'=> true
                ) );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($data),
                ),
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $branchsData = json_decode($result, True);
            foreach($branchsData['result']['data'] as $branch){
                $newBranch = new BankBranches();
                $newBranch->address = $branch['address'];

                $yandexGeoDecode = file_get_contents('http://geocode-maps.yandex.ru/1.x/?format=json&results=1&geocode=город Москва, '. $newBranch->address);
                $answer = json_decode($yandexGeoDecode,True);
                if(count($answer['response']['GeoObjectCollection']['featureMember']) == 0){
                    continue;
                }
                $pos = $answer['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
                $pos = explode(' ',$pos);
                $newBranch->latitude = $pos[0];
                $newBranch->longtitude = $pos[1];

//                $newBranch->bank_id = $branch['bank_id'];
                $newBranch->save();
                $newBranch->bank_id = $this->banksId[$branch['bank_id']];
                $newBranch->save();
            }
        }
    }

}