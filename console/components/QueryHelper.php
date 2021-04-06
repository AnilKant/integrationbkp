<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 12/7/18
 * Time: 4:02 PM
 */
namespace console\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

class QueryHelper extends Component
{
    public static function sqlRecords($query, $bindParams=[], $queryType, $dbName=null,$check=null)
    {
        if(is_null($dbName))
            $connection = Yii::$app->getDb();
        else
            $connection = Yii::$app->get($dbName);

        $command = $connection->createCommand($query);

        if(is_array($bindParams)) {
            foreach ($bindParams as $bindKey => $bindValue) {
                $command->bindValue($bindKey, $bindValue);
            }
        }
        if($check){
            print_r($bindParams);
            var_dump($command->rawSql);
            die;
        }
        //echo $command->rawSql;die;
        $response = [];

        if($queryType == "update" || $queryType == "delete" || $queryType == "insert") {
            //print_r($command);die('param');
            $response = $command->execute();
        }
        elseif($queryType == "one") {
            $response = $command->queryOne();
            //print_r($response);die("99");
        }
        elseif($queryType == "column") {
            $response = $command->queryColumn();
        }
        else {
            $response = $command->queryAll();
        }

        unset($connection);
        //print_r($response);die;
        return $response;
    }
}