<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend;

use Yii;

class Application extends \yii\web\Application
{
	public $dbConnection = false;


	public function getDb($force=false) {
		
		if(!$this->dbConnection || $force)
		{
			if($id = $this->request->get('ext', false)){
				if($data = $this->getDbConnctionThroughId($id)){					
					$this->dbConnection = $this->get($data['db_name']);
				}
		
			}
			elseif($shopName = $this->request->get('shop', false)){
				if($data = $this->getDbConnctionThroughShop($shopName)){
					$this->dbConnection = $this->get($data['db_name']);
				}
			}
			elseif(isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'])){
				$shopName = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
				if($data = $this->getDbConnctionThroughShop($shopName)){
					$this->dbConnection = $this->get($data['db_name']);
				}
			}
			elseif($shopName = $this->request->post('shopName', false)){
				if($data = $this->getDbConnctionThroughShop($shopName)){
					$this->dbConnection = $this->get($data['db_name']);
				}
			}
			elseif($shopName = $this->request->post('myshopify_domain', false)){
				if($data = $this->getDbConnctionThroughShop($shopName)){
					$this->dbConnection = $this->get($data['db_name']);
				}
			}
			elseif($id = $this->getSession()->get('__id')){
				if($data = $this->getDbConnctionThroughId($id)){
					$this->dbConnection = $this->get($data['db_name']);
				}
			}
			elseif($merchant_id = $this->request->post('merchant_id', false)){
				if($data = $this->getDbConnctionThroughId($merchant_id)){
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
		$id = $this->getMerchantId($id);
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

	/**
	 * get Merchant Id
	 */
	public function getMerchantId($id)
	{
	    if(class_exists('\frontend\modules\referral\models\SubUser'))
	    {
	        $seperator = \frontend\modules\referral\models\SubUser::SUBUSER_ID_SEPERATOR;

    		if (strrpos($id, $seperator) !== false)
        	{
        		$ids = explode($seperator, $id);
        		$merchantId = $ids[0];
        		
            	return $merchantId;
        	}
	    }
	    
	    return $id;
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

	public function getModuleId($id)
    {
        if (property_exists(Yii::$app,'user') && !\Yii::$app->user->isGuest) {
            $merchant_id = Yii::$app->user->identity->id;
            if ($merchant_id > 1 && $id == 'walmart') {
                $id = 'walmartnew';
            }
        } else {
            if ($id == 'walmart') {
                $id = 'walmartnew';
            }
        }
    }
}