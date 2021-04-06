<?php

namespace console\components;
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 9/10/17
 * Time: 1:05 PM
 */

use Yii;
use yii\base\Component;

class TableExists extends Component
{
	
	public static function tableExists($table)
	{
		//echo Yii::$app->db->schema->getRawTableName($table);die;
		return (Yii::$app->db->schema->getTableSchema($table) !== null);
	}
	
	/* public static function dropTableIfExists($table)
	 {
		 //echo $this->tableExists($table);die;
		 if (self::tableExists($table))
		 {
			 echo " table $table exists, drop it";
			 return ;
			 //dropTable($table);
		 }
	 }*/
	
	public static function columnExists($table, $column)
	{
		$schema = Yii::$app->db->schema->getTableSchema($table);
		if($schema) {
			if(is_array($schema->columns) && isset($schema->columns[$column])) {
				return true;
			} else {
				return false;
			}
		}
		else {
			return false;
		}
	}

    /**
     * @param $table
     * @param $index_id
     * @return bool true if index exist in table
     * @throws \yii\base\NotSupportedException
     */
    public static function indexExists($table,$index_id)
    {
        if($index_id && $table)
        {
            $schema = Yii::$app->db->getTableSchema($table);
            if($schema)
            {
                $table_indexes = Yii::$app->db->schema->findUniqueIndexes($schema);

                if(isset($schema->foreignKeys))
                    $table_indexes = array_merge($schema->foreignKeys,$table_indexes);

                $allTableKeys = self::getAllTableKeys($table);

                if(!empty($allTableKeys))
                {
                    $table_indexes = array_merge($allTableKeys,$table_indexes);
                }

                if(is_array($table_indexes) && array_key_exists($index_id,$table_indexes))
                    return true;
            }
        }
        return false;
    }

    public function getAllTableKeys($table)
    {
        $query = "SHOW INDEXES FROM " . $table;
        $query_result = QueryHelper::sqlRecords($query, [], 'all');
        $returnArray = [];
        if($query_result)
        {
            $returnArray = array_column($query_result,'Key_name','Key_name');
        }
        return $returnArray;
    }
}

