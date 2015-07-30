<?php
class BanksGetParserCommand extends CConsoleCommand{

    /*
     * @todo 1) положить в кеш 2) очистить обновить 3) новые данные в кеш
     *
    */
    public function run($args){
        $mem_start = memory_get_usage();

        $url = 'http://www.banki.ru/api/';
        $data = array('id'=>'1',
            'method'=>'bankInfo/getBankList',
            'jsonrpc'=>'2.0',
            'params' =>array(
                'region_id'=>array(4),
                'show_on_banki'=>array(0,1,2)
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
        $banks = json_decode($result, True);

        if(isset($banks['error'])){
            print $banks['error'];
            die();
        }
        $bankNewId = array();
        foreach($banks['result']['data'] as $bankInfo){
            switch($bankInfo['region']){
                case 'Москва':

                    $source_bank_id = $bankInfo['bank_id'];
                    $bank = BankResources::model()->getBankResource(Yii::app()->params['sourceId']['bankiRu'], $source_bank_id)->find();

                    if(!$bank){
                        $bank = new Bank();
                        $bank->name = $bankInfo['bank_name'];
                        $bank->address = $bankInfo['region'];
                        $bank->name = $bankInfo['bank_name'];
                        $bank->save();

                        $bank_source = new BankResources();
                        $bank_source->bank_id = $bank->id;
                        $bank_source->source_id = Yii::app()->params['sourceId']['bankiRu'];
                        $bank_source->source_alias = $source_bank_id;
                        $bank_source->save();
                    }

                    $bankNewId[$source_bank_id]=$bank->id;
                    break;
                default:
                    break;
            }

        }

        $data = array('id'=>'2',
            'method'=>'bankGeo/getObjectsByFilter',
            'jsonrpc'=>'2.0',
            'params' =>array(
                'bank_id'=>array_keys($bankNewId),
                'limit'=>100000000,
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

        if(isset($branchsData['error'])){
            print_r($branchsData['error']);
            die();
        }
        foreach($branchsData['result']['data'] as $branch){
            $newBranch = new BankBranches();
            $newBranch->address = $branch['address'];

            $data = array('id'=>'3',
                'method'=>	'bank/getBankObjectsData',
                'jsonrpc'=>'2.0',
                'params' =>array(
                    'id_list' => array($branch['id'])
                ) );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($data),
                ),
            );

            $context  = stream_context_create($options);
            $result = json_decode(file_get_contents($url, false, $context), true);
//
//            $yandexGeoDecode = file_get_contents('http://geocode-maps.yandex.ru/1.x/?format=json&results=1&geocode=город Москва, '. $newBranch->address);
//            $answer = json_decode($yandexGeoDecode,True);
//            if(count($answer['response']['GeoObjectCollection']['featureMember']) == 0){
//                continue;
//            }
//            $pos = $answer['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
//            $pos = explode(' ',$pos);
            $newBranch->latitude = $branch['latitude'];
            $newBranch->longtitude = $branch['longitude'];
            $newBranch->bank_id = $bankNewId[$branch['bank_id']];
            if(!isset($result['error'])){
                $newBranch->phone = $result['result']['data'][0]['phone'];
            }
            $newBranch->save();
        }

        echo memory_get_usage() - $mem_start;

    }
}
