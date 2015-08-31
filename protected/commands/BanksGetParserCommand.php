<?php
class BanksGetParserCommand extends CConsoleCommand{

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
        $banksData = array();
        $bankNewId = array();
        foreach($banks['result']['data'] as $bankInfo){
            switch($bankInfo['region']){
                case 'Москва':
                    $source_bank_id = $bankInfo['bank_id'];
                    $bank = Bank::model()->getBankResource(Yii::app()->params['sourceId']['bankiRu'], $source_bank_id)->find();
                    if(!$bank){
                        $bank = new Bank();
                        $bank->name = $bankInfo['bank_name'];
                        $bank->source_id = Yii::app()->params['sourceId']['bankiRu'];
                        $bank->source_alias = $source_bank_id;
                        $bank->save();
                    }

                    $bankNewId[$source_bank_id]=$bank->id;
                    $banksData[$bank->id] = $bank;
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
        $branchData = json_decode($result, True);

        if(isset($branchData['error'])){
            print_r($branchData['error']);
            die();
        }
        foreach($branchData['result']['data'] as $branch){
            $newBranch = BankBranches::model()->getBranchesResource(Yii::app()->params['sourceId']['bankiRu'], $branch['id'])->find();
            if(!$newBranch){
                $newBranch = new BankBranches();
                $newBranch->address = $branch['address'];

                $data = array('id'=>'3',
                    'method'=>	'bank/getBankObjectsData',
                    'jsonrpc'=>'2.0',
                    'params' =>array(
                        'id_list' => array($branch['id'])
                    ));

                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => json_encode($data),
                    ),
                );

                $context  = stream_context_create($options);
                $result = json_decode(file_get_contents($url, false, $context), true);

                $newBranch->latitude = $branch['latitude'];
                $newBranch->longtitude = $branch['longitude'];
                $newBranch->bank_id = $bankNewId[$branch['bank_id']];
                if(!isset($result['error'])){
                    $ph = $newBranch->preparePhone($result['result']['data'][0]['phone']);
                    if($result['result']['data'][0]['is_main_office'] == 1){
                        if(isset($banksData[$bankNewId[$branch['bank_id']]])){
                            $b = $banksData[$bankNewId[$branch['bank_id']]];
                            $b->phone = $ph;
                            $b->save();
                        }
                    }
                }
                $newBranch->save();
            }
        }

        echo memory_get_usage() - $mem_start;

    }
}
