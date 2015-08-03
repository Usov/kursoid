<?php
class ParserCommand extends CConsoleCommand{

    public function run($args){
        $mem_start = memory_get_usage();

        $bankiRuData = new BankiRuParser('bankiRu');
        $bankiRuData->currencyType = 0;
        $bankiRuData->parse();

        $rbcDataDollar = new RbcParser('rbc');
        $rbcDataDollar->currencyType = 1;
        $rbcDataDollar->parse();

        $rbcDataEuro = new RbcParser('rbc');
        $rbcDataEuro->currencyType = 2;
        $rbcDataEuro->parse();

        ApiData::getRates(False);  # обновим кеш, чтобы в нем были валидные данные, пока мы над базой колдуем
        ApiData::getCoordinates(False);
        ApiData::getDepartments(False);

        $connection=Yii::app()->db;
        $transaction=$connection->beginTransaction();
        try
        {
            BankCourses::model()->deleteAll();
            $bankiRuData->save();
            $rbcDataDollar->save();
            $rbcDataEuro->save();
            $transaction->commit();
        }
        catch(Exception $e) // в случае возникновения ошибки при выполнении одного из запросов выбрасывается исключение
        {
            print '---------------------------------------------------';
            print_r($e);
            $transaction->rollback();
        }

        ApiData::getRates(False); # обновим кеш, чтобы в нем были обновленные данные
        ApiData::getCoordinates(False);
        ApiData::getDepartments(False);

        echo memory_get_usage() - $mem_start;

    }
}
