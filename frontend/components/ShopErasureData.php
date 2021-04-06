<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 11/6/18
 * Time: 1:11 PM
 */

namespace frontend\components;

use frontend\modules\walmart\components\Dashboard\Earninginfo as WalmartEarningInfo;
use frontend\modules\walmart\components\Dashboard\OrderInfo as WalmartOrderInfo;
use frontend\modules\walmart\components\Dashboard\Productinfo as WalmartProductInfo;
use frontend\modules\walmart\components\Data as WalmartData;
use frontend\modules\walmart\components\Walmartappdetails;

use frontend\modules\jet\components\Dashboard\Earninginfo as JetEarningInfo;
use frontend\modules\jet\components\Dashboard\OrderInfo as JetOrderInfo;
use frontend\modules\jet\components\Dashboard\Productinfo as JetProductInfo;
use frontend\modules\jet\components\Data as JetData;
use frontend\modules\jet\components\Jetappdetails;

use frontend\modules\neweggmarketplace\components\Dashboard\Earninginfo as NeweggEarningInfo;
use frontend\modules\neweggmarketplace\components\Dashboard\OrderInfo as NeweggOrderInfo;
use frontend\modules\neweggmarketplace\components\Dashboard\Productinfo as NeweggProductInfo;
use frontend\modules\neweggmarketplace\components\Data as NeweggData;
use frontend\modules\neweggmarketplace\components\Helper as NeweggHelper;
use frontend\modules\neweggmarketplace\components\Neweggappdetail;

use frontend\modules\neweggcanada\components\Dashboard\Earninginfo as NeweggCaEarningInfo;
use frontend\modules\neweggcanada\components\Dashboard\OrderInfo as NeweggCaOrderInfo;
use frontend\modules\neweggcanada\components\Dashboard\Productinfo as NeweggCaProductInfo;
use frontend\modules\neweggcanada\components\Data as NeweggCaData;
use frontend\modules\neweggcanada\components\Helper as NeweggCaHelper;
use frontend\modules\neweggcanada\components\Neweggappdetail as Neweggcaappdetail;

// Sears imports
use frontend\modules\sears\components\Dashboard\Earninginfo as SearsEarningInfo;
use frontend\modules\sears\components\Dashboard\OrderInfo as SearsOrderInfo;
use frontend\modules\sears\components\Dashboard\Productinfo as SearsProductInfo;
use frontend\modules\sears\components\Data as SearsData;
use frontend\modules\sears\components\Searsappdetails;

use frontend\components\Data;
use Yii;
use yii\base\Component;

class ShopErasureData extends Component
{
	const WALMART_APP  = 'walmart';
	const JET_APP      = 'jet';
	const NEWEGG_APP   = 'newegg';
	const NEWEGGCA_APP = 'neweggca';
	const SEARS_APP    = 'sears';
	
	private $tableList   = [];
	private $shopurl     = '';
	private $marketplace = '';
	
	public function __construct($shopUrl,$marketplace)
	{
	    $this->shopurl = $shopUrl;
	    $this->marketplace = $marketplace;
	    
		$this->tableList = [
			'jet' => [
				'jet_shop_details'
			],
			'walmart' => [
				'walmart_shop_details'
			],
			'newegg' => [
				'newegg_shop_detail',
			],
			'neweggca' => [
				'newegg_can_shop_detail'
			],
			'sears' => [
				'sears_shop_details'
			],
		];
	}
	
	/**
	 * @param $shop_url
	 * @param $marketplace
	 * @return array
	 */
	public function getWalmartData()
	{
		$returnArr = [];
        $userData = $this->getUserData($this->shopurl);
        if ($userData) {
            $returnArr['merchant_id'] = $userData['id'];
            $returnArr['shop_url'] = $this->shopurl;
            $returnArr['marketplace'] = $this->marketplace;
            $mobile = [];
            $shopData = $this->getTableData($userData['id'], 'walmart_shop_details');
            if (!isset($shopData['merchant_id']))
                return ['error' => true, 'message' => "shopify store : $this->shopurl doesn't exist in $this->marketplace shop detail"];
            
            $extensionData = $this->getTableData($userData['id'], 'walmart_extension_detail');
            
            $shop_json = json_decode($shopData['shop_data'], true);
            if (!empty($shop_json)) {
                $returnArr['shopify_plan_name'] = $shop_json['plan_name'];
                if (!empty($shop_json['phone'])) {
                    $mobile[] = $shop_json['phone'];
                }
            }
            
            $returnArr['shop_data'] = $shopData['shop_data'];
            if (!is_null($userData['mobile']))
                $mobile[] = $userData['mobile'];
            
            $walmart_config = WalmartData::getConfiguration($userData['id']);
            $returnArr['marketplace_configuration'] = json_encode($walmart_config);
            
            $payment = $this->getLastPayment($userData['id'], 'walmart_recurring_payment');
            $returnArr['last_purchased_plan'] = $payment['plan_type']??null;
            
            $returnArr['mobile'] = implode(',', $mobile);
            $returnArr['email'] = $shopData['email'];
            $returnArr['total_products'] = WalmartProductInfo::getTotalProducts($userData['id']);
            $returnArr['total_orders']   = WalmartOrderInfo::getTotalOrdersCount($userData['id']);
            $returnArr['total_revenue']  = WalmartEarningInfo::getTotalEarning($userData['id']);
            
            $returnArr['purchased_status'] = $extensionData['status'];
            $returnArr['install_date'] = $extensionData['install_date'];
            $returnArr['uninstall_date'] = $extensionData['uninstall_date'];
            $returnArr['expire_date'] = $extensionData['expire_date'];
        }
		return $returnArr;
	}
	
	/**
	 * @param $shop_url
	 * @param $marketplace
	 * @return array
	 */
	public function getJetData()
	{
		$returnArr = [];
        $userData = $this->getUserData($this->shopurl);
        if (isset($userData['id'])) {
            $returnArr['merchant_id'] = $userData['id'];
            $returnArr['shop_url'] = $this->shopurl;
            $returnArr['marketplace'] = $this->marketplace;
            $mobile = [];
            $shopData = $this->getTableData($userData['id'], 'jet_shop_details');
            if (!isset($shopData['merchant_id']))
                return ['error' => true, 'message' => "shopify store : $this->shopurl doesn't exist in $this->marketplace shop detail"];
                
            $shop_json = json_decode($shopData['shop_data'], true);
            if (!empty($shop_json)) {
                $returnArr['shopify_plan_name'] = $shop_json['plan_name'];
                if (!empty($shop_json['phone'])) {
                    $mobile[] = $shop_json['phone'];
                }
            }
            $returnArr['shop_data'] = $shopData['shop_data'];
            if (!is_null($userData['mobile'])) {
                $mobile[] = $userData['mobile'];
            }
            $jet_config = Jetappdetails::getConfiguration($userData['id']);
            $returnArr['marketplace_configuration'] = json_encode($jet_config);
           
            $payment = $this->getLastPayment($userData['id'], 'jet_recurring_payment');
            $returnArr['last_purchased_plan'] = $payment['plan_type']??null;
            
            $returnArr['email'] = $shopData['email'];
            $returnArr['purchased_status'] = $shopData['purchase_status'];
            $returnArr['install_date'] = $shopData['installed_on'];
            $returnArr['uninstall_date'] = $shopData['uninstall_date'];
            $returnArr['expire_date'] = $shopData['expired_on'];
    
            $returnArr['mobile'] = implode(',', $mobile);
            
            $returnArr['total_products'] = JetProductInfo::getTotalProducts($userData['id']);
            $returnArr['total_orders']   = JetOrderInfo::getTotalOrdersCount($userData['id']);
            $returnArr['total_revenue']  = JetEarningInfo::getTotalEarning($userData['id']);
        }
		return $returnArr;
	}
    
    /**
     * @param $shop_url
     * @param $marketplace
     * @return array
     */
    public function getSearsData()
    {
        $returnArr = [];
        $prodObj    = new SearsProductInfo();
        $orderObj   = new SearsOrderInfo();
        $earningObj = new SearsEarningInfo();
        
        $userData      = $this->getUserData($this->shopurl);
        if ($userData) {
            $returnArr['merchant_id'] = $userData['id'];
            $returnArr['shop_url'] = $this->shopurl;
            $returnArr['marketplace'] = $this->marketplace;
            $mobile = [];
            $shopData = $this->getTableData($userData['id'], 'sears_shop_details');
            $shopExtnData  = $this->getTableData($userData['id'], 'sears_extension_detail');
            
            if (!isset($shopData['merchant_id']))
                return ['error' => true, 'message' => "shopify store : $this->shopurl doesn't exist in $this->marketplace shop detail"];
            
            if (!is_null($userData['mobile'])) {
                $mobile[] = $userData['mobile'];
            }
            $sears_config=SearsData::getConfiguration($userData['id']);
            
            $returnArr['marketplace_configuration']=json_encode($sears_config);
            if ($payment = $this->getLastPayment($userData['id'], 'sears_recurring_payment')) {
                $returnArr['last_purchased_plan'] = $payment['plan_type'];
            }
            $returnArr['mobile'] = implode(',', $mobile);
            $returnArr['email'] = $shopData['email'];
            $returnArr['token'] =  $shopData['token'];
            $returnArr['total_products'] = $prodObj->getTotalProducts($userData['id']);
            $returnArr['total_orders']   = $orderObj->getTotalOrdersCount($userData['id']);
            $returnArr['total_revenue']  = $earningObj->getTotalEarning($userData['id']);
            
            $returnArr['purchased_status'] = isset($shopExtnData['status'])?$shopExtnData['status']:"";
            $returnArr['install_date'] = isset($shopExtnData['install_date'])?$shopExtnData['install_date']:"";
            $returnArr['uninstall_date'] = isset($shopExtnData['uninstall_date'])?$shopExtnData['uninstall_date']:"";
            $returnArr['expire_date'] = isset($shopExtnData['expire_date'])?$shopExtnData['expire_date']:"";
        }
        return $returnArr;
    }
    
	/**
	 * @param $shop_url
	 * @param $marketplace
	 * @return array
	 */
	public function getNeweggData()
	{
		$returnArr = [];
        $userData = $this->getUserData($this->shopurl);
        if ($userData) {
            $returnArr['merchant_id'] = $userData['id'];
            $returnArr['shop_url'] = $this->shopurl;
            $returnArr['marketplace'] = $this->marketplace;
            $mobile = [];
            $shopData = $this->getTableData($userData['id'], 'newegg_shop_detail');
    
            if (!isset($shopData['merchant_id']))
                return ['error' => true, 'message' => "shopify store : $this->shopurl doesn't exist in $this->marketplace shop detail"];
            
            $shop_json = json_decode($shopData['client_data'], true);
            if (!empty($shop_json)) {
                $returnArr['shopify_plan_name'] = $shop_json['plan_name'];
                if (!empty($shop_json['phone'])) {
                    $mobile[] = $shop_json['phone'];
                }
            }
            $returnArr['shop_data'] = $shopData['client_data'];
            $returnArr['email'] = $shopData['email'];
            $returnArr['purchased_status'] = $shopData['purchase_status'];
            $returnArr['install_date'] = $shopData['install_date'];
            $returnArr['uninstall_date'] = $shopData['uninstall_date'];
            $returnArr['expire_date'] = $shopData['expire_date'];
            
            if (!is_null($userData['mobile'])) {
                $mobile[] = $userData['mobile'];
            }
            $newegg_config = NeweggHelper::configurationDetail($userData['id']);
            $returnArr['marketplace_configuration'] = json_encode($newegg_config);
            
            $payment = $this->getLastPayment($userData['id'], 'newegg_payment');
            $returnArr['last_purchased_plan'] = $payment['plan_type']??null;
            
            $returnArr['mobile'] = implode(',', $mobile);
            
            $returnArr['total_products'] = NeweggProductInfo::getTotalProducts($userData['id']);
            $returnArr['total_orders']   = NeweggOrderInfo::getTotalOrdersCount($userData['id']) ;
            $returnArr['total_revenue']  = NeweggEarningInfo::getTotalEarning($userData['id']);
        }
		return $returnArr;
	}
	
	/**
	 * @param $shop_url
	 * @param $marketplace
	 * @return array
	 */
	public function getNeweggcaData()
	{
		$returnArr = [];
        $userData = $this->getUserData($this->shopurl);
        if ($userData) {
            $returnArr['merchant_id'] = $userData['id'];
            $returnArr['shop_url'] = $this->shopurl;
            $returnArr['marketplace'] = $this->marketplace;
            $mobile = [];
            $shopData = $this->getTableData($userData['id'], 'newegg_can_shop_detail');
    
            if (!isset($shopData['merchant_id']))
                return ['error' => true, 'message' => "shopify store : $this->shopurl doesn't exist in $this->marketplace shop detail"];
            
            $shop_json = json_decode($shopData['client_data'], true);
            if (!empty($shop_json)) {
                $returnArr['shopify_plan_name'] = $shop_json['plan_name'];
                if (!empty($shop_json['phone'])) {
                    $mobile[] = $shop_json['phone'];
                }
            }
            $returnArr['shop_data'] = $shopData['client_data'];
            $returnArr['email'] = $shopData['email'];
            $returnArr['purchased_status'] = $shopData['purchase_status'];
            $returnArr['install_date'] = $shopData['install_date'];
            $returnArr['uninstall_date'] = $shopData['uninstall_date'];
            $returnArr['expire_date'] = $shopData['expire_date'];
            if (!is_null($userData['mobile'])) {
                $mobile[] = $userData['mobile'];
            }
            
            $neweggca_config = NeweggCaHelper::configurationDetail($userData['id']);
            $returnArr['marketplace_configuration'] = json_encode($neweggca_config);
            
            $payment = $this->getLastPayment($userData['id'], 'newegg_can_payment');
            $returnArr['last_purchased_plan'] = $payment['plan_type']??null;
            
            $returnArr['mobile'] = implode(',', $mobile);
            
            $returnArr['total_products'] = NeweggCaProductInfo::getTotalProducts($userData['id']);
            $returnArr['total_orders'] = NeweggCaOrderInfo::getTotalOrdersCount($userData['id']) ;
            $returnArr['total_revenue'] = NeweggCaEarningInfo::getTotalEarning($userData['id']);
        }
		
		return $returnArr;
		
	}
	
	/**
	 * @param $shop_url
	 * @return array|bool|int
	 */
	public function getUserData($shop_url)
	{
		$query = "SELECT * FROM `user` WHERE `username`=:username LIMIT 0,1";
		return QueryHelper::sqlRecords($query, [':username'=>$shop_url], "one");
	}
	
	/**
	 * @param $merchant_id
	 * @return array|bool|int
	 */
	public function getTableData($merchant_id, $table_name)
	{
		if ($table_name=='user')
			$query = "SELECT * FROM `user` WHERE `id`='" . $merchant_id . "' LIMIT 0,1";
		else
			$query = "SELECT * FROM {$table_name} WHERE `merchant_id`='" . $merchant_id . "' LIMIT 0,1";
		$shopData = Data::sqlRecords($query, "one", "select");
		return $shopData;
	}
	
	/**
	 * @param $update_data
	 * @return array|bool|int|string
	 */
	public function insertData($update_data)
	{
		$response = [];
		
		if (is_array($update_data)) {
			$shopErasureData = QueryHelper::sqlRecords('SELECT * FROM `shop_erasure_data` WHERE `marketplace` = :marketplace AND `merchant_id`=:mid', [':marketplace'=>$update_data['marketplace'], ':mid'=>$update_data['merchant_id']], 'one');
			try{
                QueryHelper::sqlRecords("START TRANSACTION;", [], 'execute');
                if (!$shopErasureData) {
                    $bindParamKey = $insertColumn = $bindParamValue = [];
                    foreach ($update_data as $key => $value) {
                        $insertColumn[] = "`$key`";
                        
                        $bindParamKey[] = ":$key";
                        $bindParamValue[":$key"] = $value;
                    }
                    
                    if(!empty($bindParamKey)) {
                        $query = 'INSERT INTO `shop_erasure_data` (' . implode(',', $insertColumn) . ') VALUES (' . implode(',', $bindParamKey) . ')';
                        $response = QueryHelper::sqlRecords($query, $bindParamValue, 'insert');
                    }
                } else {
                    $bindParamKey = [];
                    
                    $bindParamValue[':shop'] = $update_data['shop_url'];
                    $bindParamValue[':marketplace'] = $update_data['marketplace'];
                    
                    foreach ($update_data as $key => $value) {
                        if($shopErasureData[$key] != $value) {
                            $bindParamKey[] = "$key = :$key";
                            $bindParamValue[":$key"] = $value;
                        }
                    }
                    
                    if(!empty($bindParamKey)) {
                        $query = 'UPDATE `shop_erasure_data` SET ' . implode(',', $bindParamKey) . ' WHERE `shop_url` = :shop AND `marketplace` = :marketplace';
                        $response = QueryHelper::sqlRecords($query, $bindParamValue, 'update');
                    }
                }
                QueryHelper::sqlRecords("COMMIT;", [], 'execute');
            } catch (yii\db\Exception $e){
                QueryHelper::sqlRecords("ROLLBACK;", [], 'execute');
			    return false;
            } catch (Exception $e){
			    return  false;
            }
		}
		
		return $response;
	}
	
	/**
	 * @param $marketpalce
	 * @param $merchant_id
	 */
	public function deleteData($marketpalce, $merchant_id)
	{
		$returnRes = false;
		if (isset($this->tableList[$marketpalce])) {
			$tables = $this->tableList[$marketpalce];
			$removeProducts = $this->removeMarketplace($this->shopurl, $marketpalce);
			if ($removeProducts) {
				$tables[] = 'jet_product';
				$tables[] = 'jet_product_variants';
				$tables[] = 'user';
				$tables[] = 'merchant_db';
			}
			foreach ($tables as $table_name) {
				if ($table_name =='user')
					$query = "DELETE FROM `user` WHERE `id` =:m_id";
				else
					$query = "DELETE FROM {$table_name} WHERE `merchant_id` =:m_id";
				$res = QueryHelper::sqlRecords($query,[':m_id'=>$merchant_id],'delete');
				if ($res)
					$returnRes = true;
			}
		}
		return $returnRes;
	}
	
	/**
	 * @param $merchant_id
	 * @param $table_name
	 * @return array|bool|int
	 */
	public function getLastPayment($merchant_id, $table_name)
	{
        return QueryHelper::sqlRecords("SELECT * FROM {$table_name} WHERE `merchant_id`=:merchant_id ORDER BY `activated_on` DESC",[':merchant_id'=>$merchant_id] ,'one');
	}
	
	/**
	 * @param $shop_name
	 * @param $marketplace
	 * @return bool
	 */
	public function removeMarketplace($shop_name, $marketplace)
	{
		$deleteJetDataFlag = true;
		$install_on_walmart = false;
		$install_on_jet = false;
		$install_on_neweggca = false;
		$install_on_newegg = false;
		$install_on_sears = false;
		
		if (!$shop_name || is_null($shop_name)) {
			return $deleteJetDataFlag;
		}
		$marketplaceList = [
			'jet' => 'jet_shop_details',
			'walmart' => 'walmart_shop_details',
			'newegg' => 'newegg_shop_detail',
			'neweggca' => 'newegg_can_shop_details',
			'sears' => 'sears_shop_details'
		];
		
		unset($marketplaceList[$marketplace]);// unset marketplace
		
		foreach ($marketplaceList as $marketplace_name => $table_name) {
			
			switch ($marketplace_name) {
				case self::WALMART_APP:
					$app_status = Walmartappdetails::appstatus($shop_name);
					if ($app_status) {
						$install_on_walmart = true;
					}
					break;
				case self::NEWEGG_APP:
					$app_status = Neweggappdetail::appstatus($shop_name);
					if ($app_status) {
						$install_on_newegg = true;
					}
					break;
				case self::JET_APP:
					$app_status = Jetappdetails::appstatus($shop_name);
					if ($app_status) {
						$install_on_jet = true;
					}
					break;
				case self::NEWEGGCA_APP:
					$app_status = Neweggcaappdetail::appstatus($shop_name);
					if ($app_status) {
						$install_on_neweggca = true;
					}
					break;
				case self::SEARS_APP:
					$app_status = Searsappdetails::appstatus($shop_name);
					if ($app_status) {
						$install_on_sears = true;
					}
					break;
			}
		}
		
		if ($install_on_jet || $install_on_newegg || $install_on_neweggca || $install_on_sears || $install_on_walmart) {
			$deleteJetDataFlag = false;
		}
		
		return $deleteJetDataFlag;
	}
	
}