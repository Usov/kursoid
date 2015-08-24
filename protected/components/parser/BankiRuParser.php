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
}