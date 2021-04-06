<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend;

use Yii;

class ConsoleApplication extends \yii\console\Application
{
	public $merchant_id = false;

	public $shop_name = false;

	public $dbConnection = false;
	
	public function getDb($force=false) {
		
		if(!$this->dbConnection || $force)
		{
			if($id = $this->merchant_id){
				if($data = $this->getDbConnctionThroughId($id)){
					$this->dbConnection = $this->get($data['db_name']);
				}
			}
			elseif($shopName = $this->shop_name){
				if($data = $this->getDbConnctionThroughShop($shopName)){
					$this->dbConnection = $this->get($data['db_name']);
				}
			}

			if(!$this->dbConnection){
				//return current db name
				$dbName = $this->getCurrentDb();
				$this->dbConnection = $this->get($dbName);
			}
		}

		return $this->dbConnection;
	}

	public function getDbConnctionThroughId($id){
		$baseDb = $this->getBaseDb();
		$connection = $this->get($baseDb);
		return $connection->createCommand("SELECT db_name FROM merchant_db WHERE merchant_id='{$id}'")->queryOne();
	}

	public function getDbConnctionThroughShop($shop){
		$baseDb = $this->getBaseDb();
		$connection = $this->get($baseDb);
		return $connection->createCommand("SELECT db_name FROM merchant_db WHERE shop_name='{$shop}'")->queryOne();
	}

	/**
	 * Name of databse in which new merchants will be stored.
	 */
	public function getCurrentDb()
	{
		return 'db';
	}

	/**
	 * Name of databse in which `merchant_db` table is created.
	 */
	public function getBaseDb()
	{
		return 'db';
	}

	public function getDbList()
	{
		$dbList = [];

		$webroot =  Yii::getAlias('@webroot');
        $filePath = $webroot.'/common/config/main-local.php';
        if (file_exists($filePath)) 
        {
            $config = require $filePath;
            $components = isset($config['components']) ? $config['components'] : [];
            foreach ($components as $compKey => $compData) 
            {
                if(isset($compData['class']) && $compData['class']=='yii\db\Connection'){
                    $dbList[] = $compKey;
                }
            }
        }

        return $dbList;
	}
}