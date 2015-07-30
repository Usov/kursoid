<?php

class DownloadUrlCreator {
    const RBC_URL = 'http://quote.rbc.ru/cgi-bin/front/content/cash_currency_rates/?sortf=DT_LAST_PUBLICATE&sortd=DESC&city=%city_id%&currency=%currency_id%&summa=&period=60&pagerLimiter=30000&pageNumber=1&r=0.251808350172383';
    const BANKIRU_URL = 'http://www.banki.ru/products/currency/cash/moskva/';

    public static function rbc($currency_id=1, $city_id=1){
        $url = str_replace(array("%city_id%", "%currency_id%"),
        array($city_id, $currency_id),
        DownloadUrlCreator::RBC_URL);

        return $url;
    }

    public static function bankiRu(){
        return DownloadUrlCreator::BANKIRU_URL;
    }

}