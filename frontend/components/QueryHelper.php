<?php
/**
 *  Created by Amit Shukla.
 *  User: cedcoss
 *  Date: 24/4/19 3:37 PM
 *
 */

namespace frontend\components;

use Yii;
use yii\base\Component;
use frontend\components\Log;

class QueryHelper extends Component
{
    public static function executeQuery($query, $bindParams=[], $queryType, $dbName=null,$check=null)
    {
        try {
            if(is_null($dbName))
                $connection = Yii::$app->getDb();
            else
                $connection = Yii::$app->get($dbName);

            $command = $connection->createCommand($query);

            if(is_array($bindParams)) {
                $command->bindValues($bindParams);
            }
            if($check){
                var_dump($command->rawSql);die;
            }

            $response = [];

            if($queryType == "update" || $queryType == "delete" || $queryType == "insert" || $queryType == "execute") {
                $response = $command->execute();
            }
            elseif($queryType == "one") {
                $response = $command->queryOne();

            }
            elseif($queryType == "column") {
                $response = $command->queryColumn();
            }
            else {
                $response = $command->queryAll();
            }

            return $response;
        }  
        catch (\yii\db\Exception $e) {
            $message = strtolower($e->getMessage());
            if(strpos($message, 'mysql server has gone away') !== false || strpos($message, 'connection refused') !== false || strpos($message, 'error while sending query packet') !== false) {
                self::reConnectDb();
            }

            $error = "Exception : " . $message. PHP_EOL .$e->getTraceAsString();
            Log::createLog($error, 'exception/db-exception/'.microtime(true).'.log');
            
            throw new \yii\db\Exception($message);
        }
    }

    public static function sqlRecords($query, $bindParams=[], $queryType, $dbName=null,$check=null)
    {
        try {
            if(is_null($dbName))
                $connection = Yii::$app->getDb();
            else
                $connection = Yii::$app->get($dbName);
            
            $command = $connection->createCommand($query, $bindParams );
    
    
            if($check === 'log'){
                return [
                    'sql' => $command->rawSql,
                    'type' => 'query_check',
                ];
            } elseif($check === true){
                echo "<hr><pre>";
                print_r($bindParams);
                print_r($command->rawSql);
                die("<hr>QueryHelper");
            }

            $response = [];

            if($queryType == "update" || $queryType == "delete" || $queryType == "insert" || $queryType == "execute") {
                $response = $command->execute();
            }
            elseif($queryType == "one") {
                $response = $command->queryOne();

            }
            elseif($queryType == "column") {
                $response = $command->queryColumn();
            }
            else {
                $response = $command->queryAll();
            }

            return $response;
        }  
        catch (\yii\db\Exception $e) {
            $message = strtolower($e->getMessage());
            if(strpos($message, 'mysql server has gone away') !== false || strpos($message, 'connection refused') !== false) {
                self::reConnectDb();
            }

            $error = "Exception : " . $message. PHP_EOL .$e->getTraceAsString();
            Log::createLog($error, 'exception/db-exception/'.microtime(true).'.log');
            
            throw new \yii\db\Exception($message);
        }
    }

    public static function reConnectDb()
    {
        //Log::createLog('restart connection.', "connection.log");
        try{
            $connection = Yii::$app->getDb();
            $connection->close();
            $connection->open();
        } catch (\Exception $e) {
            Log::createLog($e->getMessage(), "reconnect-db.log");
        }
    }
}
