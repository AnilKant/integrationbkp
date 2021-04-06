<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

class Newegg extends Model
{
    private $connection;

    const CONFIGURATION_TABLE_NAME = '`newegg_configuration`';

    public function __construct()
    {
        $this->connection = is_null($this->connection)?$this->getConnection():$this->connection;
    }

    /**
     * Returns the database connection
     * 
     * @return Connection the database connection
     */
    public function getConnection()
    {
        return Yii::$app->getDb();
    }

    /**
     * Fetch Records From Table
     *
     * @param string $tableName Name of the table. ex : `table_name`
     * @param string $where Where clause condition. ex. : `col_name`='test'
     * @param array $columns Columns to be fetched. ex : array(`col_1`,`col_2`) || array() for all columns
     * @param string $records 'all' | 'one'
     * @return boolean
     */
    public function fetch($tableName, $where=null, $columns=array(), $records='all')
    {
        $result = false;
        $query = '';
        if($tableName != '') {
            $_columns = '*';
            if(is_array($columns) && count($columns)) {
                $_columns = implode(',', $columns);
            }

            $query = "SELECT $_columns FROM $tableName";

            if(!is_null($where))
                $query .= " WHERE $where";

            if($records == 'one')
                $query .= " LIMIT 0, 1";

            if($query != '') {
                $result = $this->connection->createCommand($query);
                if($records == 'one')
                    $result = $result->queryOne();
                else {
                    $result = $result->queryAll();
                }
            }
        }
        return $result;
    }

    /**
     * Execute the Raw Sql Query
     * 
     * @return boolean
     */
    public function executeQuery($query)
    {
        return $this->connection->createCommand($query)->execute();
    }

    /**
     * Get Configration Settings for Currently Logged in Merchant
     * 
     * @return boolean|array
     */
    public function getNeweggConfiguration()
    {
        if(!defined('MERCHANT_ID'))
            return false;

        $table_name = Newegg::CONFIGURATION_TABLE_NAME;
        $where = "`merchant_id`=".MERCHANT_ID;
        $columns = array('`consumer_channel_type_id`','`api_user`','`api_password`','`merchant_email`');
        $data  = self::fetch($table_name, $where, $columns, 'one');
        return $data;
    }
}