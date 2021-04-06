<?php
namespace console\controllers;
use console\components\QueryHelper;
use console\components\ShopifyClientHelper;
use console\components\walmart\WalmartProductSync;
use yii\console\Controller;
use frontend\modules\walmart\components\Price\PriceUpdate;
use Yii;
use yii\web;
use frontend\modules\walmart\components\Data;
use frontend\modules\walmart\components\Walmartappdetails;
use frontend\modules\walmart\components\Walmartapi;
use frontend\modules\walmart\components\Inventory\InventoryUpdate;
use frontend\modules\walmart\models\WalmartProduct;
use frontend\modules\walmart\controllers\WalmartrepricingController;
use frontend\modules\walmart\models\WalmartCronSchedule;
use frontend\modules\walmart\controllers\WalmartorderdetailController;

/**
* Cron controller
*/
class CronController extends Controller 
{

    public function beforeAction($action)
    {
        //$this->action->id;
        Data::writeFile(date("Y-m-d H:i:s"), 'cron/status.log', 'w');
        if (file_exists(Yii::getAlias('@webroot').'/maintenance.flag')) {
            return false;
        }
        return true;    
    }
  
    /**
    * Sync Orders on Walamrt
    */
    /*public function actionSyncWalmartOrder()
    {
        ob_start ();
        $obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
         
        $cron_array = array();
        $connection = Yii::$app->getDb();

        $processedMerchantCount = 0;
        $size = 50;

        $cronData = WalmartCronSchedule::find()->where(['cron_name'=>'sync_order'])->one();
        if($cronData && $cronData['cron_data'] != ""){
          $cron_array = json_decode($cronData['cron_data'],true);
        }
        else
        {
          $cron_array = Walmartappdetails::getConfig();
        }

        $status_array['total_count'] = count($cron_array);
        if(is_array($cron_array) && count($cron_array)>0)
        {
          foreach($cron_array as $k=>$Config)
          {
            try
            {
                $Config['merchant_id'] = $k;
                $obj->actionSyncorder($Config);
               
                unset($cron_array[$k]);
            }
            catch (Exception $e)
            {
              Data::createLog("order fetch exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
              unset($cron_array[$k]);
              continue;
            }
            catch(\yii\db\IntegrityException $e)
            {
              Data::createLog("order fetch db-integrity-exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
              unset($cron_array[$k]);
              continue;
            }
            catch(\yii\db\Exception $e)
            {
              Data::createLog("order fetch db-exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
              unset($cron_array[$k]);
              continue;
            }
            $processedMerchantCount++;
            if($processedMerchantCount==$size)
            break;
          }
    }

    if(count($cron_array)==0)
      $cronData->cron_data="";
    else
      $cronData->cron_data=json_encode($cron_array);

    $cronData->save(false);
    unset($cronData);
    $html = ob_get_clean();

    }*/
    public function actionSyncWalmartOrder()
    {
        ob_start ();

        $connection = Yii::$app->get(Yii::$app->getBaseDb());

        $dbList = Yii::$app->getDbList();
    
        $processedMerchantCount = 0;
        $size = 50;
        
        $result = WalmartCronSchedule::find()->where(['cron_name'=>'sync_order'])->one();
        if($result && $result['cron_data'] != "") {
            $cron_array = json_decode($result['cron_data'], true);
        }
        else {
            $cron_array = $connection->createCommand("SELECT * FROM `merchant_db` WHERE `app_name` LIKE '%".Data::APP_NAME_WALMART."%'")->queryAll();
        }

        if(is_array($cron_array) && count($cron_array))
        {
            $data = implode(',', array_keys($cron_array));
            Data::createCronstatuslog('sync',$data);
            $dbName = '';
            foreach($cron_array as $key=>$merchant)
            {
                try
                {
                    if(!in_array($merchant['db_name'], $dbList)) {
                        unset($cron_array[$key]);
                        continue;
                    }

                    if($processedMerchantCount == $size)
                        break;
                    $processedMerchantCount++;
                    unset($cron_array[$key]);
                    if($dbName != $merchant['db_name']){
                      Yii::$app->dbConnection = Yii::$app->get($merchant['db_name']);
                    }
                    $dbName = $merchant['db_name'];
                    Yii::$app->merchant_id = $merchant['merchant_id'];
                    Yii::$app->shop_name = $merchant['shop_name'];

                    $query = "SELECT `config`.`consumer_id`, `config`.`secret_key`, `shop`.`token`, `shop`.`email`, `shop`.`status` FROM `walmart_configuration` `config` INNER JOIN (SELECT `token`, `email`, `status`, `merchant_id` FROM `walmart_shop_details` WHERE `merchant_id`='{$merchant['merchant_id']}') `shop` ON `shop`.`merchant_id` = `config`.`merchant_id` WHERE `config`.`merchant_id`='{$merchant['merchant_id']}'";
                    $configData = Data::sqlRecords($query, 'one');

                    if($configData)
                    {
                        $isValidate = Walmartappdetails::isValidateapp($merchant['merchant_id']);

                        if(!$configData['status'] || $isValidate=="expire" || $isValidate=="trial_expired") {
                            continue;
                        }

                        $configData['merchant_id'] = $merchant['merchant_id'];
                        $configData['shop_url'] = $merchant['shop_name'];

                        $obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
                        $obj->actionSyncorder($configData);
                    }
                }
                catch (Exception $e)
                {
                    Data::createLog("order fetch exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\IntegrityException $e)
                {
                    Data::createLog("order fetch db-integrity-exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\Exception $e)
                {
                    Data::createLog("order fetch db-exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
            }
        }

        if(count($cron_array)==0)
          $result->cron_data="";
        else
          $result->cron_data=json_encode($cron_array);

        $result->save(false);
        $html = ob_get_clean();
    }

    /*public function actionWalmartorder()
    {
    ob_start ();
    $obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
    $cron_array = array();
    $connection = Yii::$app->getDb();
    $cronData = WalmartCronSchedule::find()->where(['cron_name'=>'fetch_order'])->one();
    if($cronData && $cronData['cron_data'] != "") {
        $cron_array = json_decode($cronData['cron_data'],true);
    }
    else {
        $cron_array = Walmartappdetails::getMerchants();
    }

    $processedMerchantCount = 0;
    $size = 100;

    if(is_array($cron_array) && count($cron_array)>0)
    {
        foreach($cron_array as $key=>$Config)
        {
          try
          {
              $merchant_id = $key;
              $walmartApiData = Walmartappdetails::getWalmartAPiDetails($merchant_id);
              $shop_detail = Data::getWalmartShopDetails($merchant_id);
              if(isset($walmartApiData['consumer_id']) && isset($walmartApiData['secret_key']))
              {
                $Config['merchant_id'] = $merchant_id;
                $Config['consumer_id'] = $walmartApiData['consumer_id'];
                $Config['secret_key'] = $walmartApiData['secret_key'];
                $Config['currency'] = $shop_detail['currency'];
                $obj->actionCreate($Config);
              }
           
              unset($cron_array[$key]);
          }
          catch (Exception $e)
          {
            Data::createLog("order fetch exception ".$e->getTraceAsString(),'walmartOrderCron/exception.log','a',true);
            unset($cron_array[$key]);
            continue;
          }
          catch(\yii\db\IntegrityException $e)
          {
            Data::createLog("order fetch db-integrity-exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
            unset($cron_array[$k]);
            continue;
          }
          catch(\yii\db\Exception $e)
          {
            Data::createLog("order fetch db-exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
            unset($cron_array[$k]);
            continue;
          }
          $processedMerchantCount++;
          if($processedMerchantCount == $size)
              break;
        }
    }

    if(count($cron_array)==0)
        $cronData->cron_data = "";
    else
        $cronData->cron_data = json_encode($cron_array);

    $cronData->save(false);
    unset($cronData);
    $html = ob_get_clean();
    }*/
    public function actionWalmartorder()
    {
        ob_start ();

        $connection = Yii::$app->get(Yii::$app->getBaseDb());

        $dbList = Yii::$app->getDbList();
    
        $processedMerchantCount = 0;
        $size = 100;
        
        $result = WalmartCronSchedule::find()->where(['cron_name'=>'fetch_order'])->one();
        if($result && $result['cron_data'] != "") {
            $cron_array = json_decode($result['cron_data'], true);
        }
        else {
            $cron_array = $connection->createCommand("SELECT * FROM `merchant_db` WHERE `app_name` LIKE '%".Data::APP_NAME_WALMART."%'")->queryAll();
        }

        if(is_array($cron_array) && count($cron_array))
        {

            $data = implode(',', array_keys($cron_array));
            Data::createCronstatuslog('create',$data);
            foreach($cron_array as $key=>$merchant)
            {
                try
                {
                    if(!in_array($merchant['db_name'], $dbList)) {
                        unset($cron_array[$key]);
                        continue;
                    }

                    if($processedMerchantCount == $size)
                        break;
                    $processedMerchantCount++;
                    unset($cron_array[$key]);

                    Yii::$app->dbConnection = Yii::$app->get($merchant['db_name']);
                    Yii::$app->merchant_id = $merchant['merchant_id'];
                    Yii::$app->shop_name = $merchant['shop_name'];

                    $query = "SELECT `config`.`consumer_id`, `config`.`secret_key`, `shop`.`token`, `shop`.`email`, `shop`.`status`, `shop`.`currency` FROM `walmart_configuration` `config` INNER JOIN (SELECT `token`, `email`, `status`, `merchant_id`, `currency` FROM `walmart_shop_details` WHERE `merchant_id`='{$merchant['merchant_id']}') `shop` ON `shop`.`merchant_id` = `config`.`merchant_id` WHERE `config`.`merchant_id`='{$merchant['merchant_id']}'";
                    $configData = Data::sqlRecords($query, 'one');

                    if($configData)
                    {
                        $isValidate = Walmartappdetails::isValidateapp($merchant['merchant_id']);

                        if(!$configData['status'] || $isValidate=="expire" || $isValidate=="trial_expired") {
                            continue;
                        }

                        $configData['merchant_id'] = $merchant['merchant_id'];
                        $configData['shop_url'] = $merchant['shop_name'];

                        $obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
                        $obj->actionCreate($configData);
                    }
                }
                catch (Exception $e)
                {
                    Data::createLog("order fetch exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\IntegrityException $e)
                {
                    Data::createLog("order fetch db-integrity-exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\Exception $e)
                {
                    Data::createLog("order fetch db-exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
            }
        }

        if(count($cron_array)==0)
          $result->cron_data="";
        else
          $result->cron_data=json_encode($cron_array);

        $result->save(false);
        $html = ob_get_clean();
    }

    /**
    * function for getting shipping data
    */
    public function getShippingItems($data){
      $items = array();
      if(!isset($data[0])){
          $data = [$data];
      }
      foreach($data as $item){
          $sku = $item['item']['sku'];
          $status = isset($item['orderLineStatuses']['orderLineStatus']['status'])?$item['orderLineStatuses']['orderLineStatus']['status']:'';
          $items[$sku][$item['lineNumber']] = array('lineNumber'=>$item['lineNumber'],'status'=>$status,'sku'=>$sku);
          
      }
      return $items;
    }

    /**
    * Sync Inventory on Walmart
    */
    /*public function actionInventoryupdate()
    {
      ob_start ();
      $cron_array = array();
      //$connection = Yii::$app->getDb();
      $cronData = WalmartCronSchedule::find()->where(['cron_name'=>'fetch_inventory'])->one();
      if($cronData && $cronData['cron_data'] != "")
      {
        $cron_array = json_decode($cronData['cron_data'],true);
      }
      else
      {
        $cron_array = Walmartappdetails::getConfig();
      }
      $start = 0;
      $countArr = 0;
      $updateInventory=[];
      if(is_array($cron_array) && count($cron_array)>0)
      {
          foreach($cron_array as $k=>$Config)
          {
              try
              {
                  $isError=false;
                  $merchant_id = $k;
                  $count=0;
                  $consumer_id = $Config['consumer_id'];
                  $secret_key = $Config['secret_key'];
                  $channel_type_id = $Config['consumer_channel_type_id'];
                  $inventoryUpdateObj = new InventoryUpdate($consumer_id, $secret_key, $channel_type_id);
                  $countArr++;
                  unset($cron_array[$k]);
                  $query='select jet.id,sku,type,qty,jet.merchant_id from `walmart_product` wal INNER JOIN `jet_product` jet ON jet.id=wal.product_id where wal.status!="Not Uploaded" and wal.merchant_id="'.$merchant_id.'"';
                  $product = Data::sqlRecords($query,"all","select");
                  if(is_array($product) && count($product)>0)
                  {
                      $response=[];
                      if(!$product['merchant_id']==810)
                      $response = $inventoryUpdateObj->updateInventoryOnWalmart($product,"product");
                      if(isset($response['errors']))
                          $error++;
                      else
                          $count=count($product);
                  }
                  $updateInventory[$merchant_id]=$count;
                  unset($cron_array[$k]);
                  if($countArr>=10)
                      break;
              }
              catch(Exception $e)
              {
                  Data::createLog("product status exception ".$e->getTraceAsString(),'productInventory/exception.log','a',true);
              }
          }
      }
      if(count($cron_array)==0)
          $cronData->cron_data="";
      else
          $cronData->cron_data=json_encode($cron_array);
      $cronData->save(false);
      var_dump($updateInventory);
      $html = ob_get_clean();
    }*/
    //changed on the basis of multiple db
    /*public function actionInventoryupdate()
    {
        ob_start ();

        $inventoryUpdate = new \console\components\InventoryUpdate();
        
        $connection = Yii::$app->get(Yii::$app->getBaseDb());

        $dbList = Yii::$app->getDbList();
    
        $processedMerchantCount = 0;
        $size = 10;
        
        $result = WalmartCronSchedule::find()->where(['cron_name'=>'fetch_inventory'])->one();

        if($result && $result['cron_data'] != "") {
            $cron_array = json_decode($result['cron_data'], true);
        }
        else {
            $cron_array = $connection->createCommand("SELECT * FROM `merchant_db` WHERE `app_name` LIKE '%".Data::APP_NAME_WALMART."%'")->queryAll();
        }

        if(is_array($cron_array) && count($cron_array))
        {
            foreach($cron_array as $key=>$merchant)
            {
                try
                {
                    $merchant_id = $merchant['merchant_id'];

                    if(!in_array($merchant['db_name'], $dbList) || $merchant_id=='810') {
                        unset($cron_array[$key]);
                        continue;
                    }

                    if($processedMerchantCount == $size)
                        break;
                    $processedMerchantCount++;
                    unset($cron_array[$key]);

                    Yii::$app->dbConnection = Yii::$app->get($merchant['db_name']);
                    Yii::$app->merchant_id = $merchant_id;
                    Yii::$app->shop_name = $merchant['shop_name'];

                    $query = "SELECT `config`.`consumer_id`, `config`.`secret_key`, `shop`.`token`, `shop`.`email`, `shop`.`status`, `shop`.`currency` FROM `walmart_configuration` `config` INNER JOIN (SELECT `token`, `email`, `status`, `merchant_id`, `currency` FROM `walmart_shop_details` WHERE `merchant_id`='{$merchant_id}') `shop` ON `shop`.`merchant_id` = `config`.`merchant_id` WHERE `config`.`merchant_id`='{$merchant_id}'";
                    $configData = Data::sqlRecords($query, 'one');

                    if($configData)
                    {
                        $isValidate = Walmartappdetails::isValidateapp($merchant_id);

                        if(!$configData['status'] || $isValidate=="expire" || $isValidate=="trial_expired") {
                            continue;
                        }

                        $inventoryUpdateObj = new InventoryUpdate($configData['consumer_id'], $configData['secret_key']);

                        $inValidStatus = "'Not Uploaded'";
                        $query="SELECT `jet`.`id`, `jet`.`sku`, `jet`.`type`, `jet`.`qty`, `jet`.`merchant_id`, `wal`.`product_qty` FROM `walmart_product` wal INNER JOIN (SELECT `id`, `sku`, `type`, `qty`, `merchant_id` FROM `jet_product` WHERE `merchant_id`='{$merchant_id}') jet ON `jet`.`id`=`wal`.`product_id` WHERE `wal`.`status` NOT IN ({$inValidStatus}) AND `wal`.`merchant_id`='{$merchant_id}'";
                        $product = Data::sqlRecords($query,"all","select");
                        if(is_array($product) && count($product)>0)
                        {                            
                            $response = $inventoryUpdateObj->updateInventoryOnWalmart($product,"product", $merchant_id);
                        }
                    }
                }
                catch (Exception $e)
                {
                    Data::createLog("product status exception ".$e->getTraceAsString(),'productInventory/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\IntegrityException $e)
                {
                    Data::createLog("order fetch db-integrity-exception ".$e->getTraceAsString(),'productInventory/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\Exception $e)
                {
                    Data::createLog("order fetch db-exception ".$e->getTraceAsString(),'productInventory/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
            }
        }

        if(count($cron_array)==0)
          $result->cron_data="";
        else
          $result->cron_data=json_encode($cron_array);

        $result->save(false);
        $html = ob_get_clean();
    }*/
    public function actionInventoryupdate($dbname=null)
    {
        ob_start ();

        $inventoryUpdate = new \console\components\walmart\InventoryUpdate($dbname);
        $inventoryUpdate->execute();
        $html = ob_get_clean();
    }



    /**
    * Walmart Repricing cron
    * 
    * @return bool
    */

    public function actionProductrepricing()
    {
        ob_start ();
        $connection = Yii::$app->get(Yii::$app->getBaseDb());

        $dbList = Yii::$app->getDbList();
    
        $processedMerchantCount = 0;
        $size = 10;
        
        $result = WalmartCronSchedule::find()->where(['cron_name'=>'product_repricing'])->one();
       
        /*if(in_array($merchant_id, $merchantsList)) {
            continue;
        }*/
        if($result && $result['cron_data'] != "") {
            $cron_array = json_decode($result['cron_data'], true);
        }
        else {
          $priceSyncDisabledMerchants = PriceUpdate::getPriceSyncDisabledMerchants();
          $params = [':app_name' => 'walmart', ':merchant' => implode(',', $priceSyncDisabledMerchants)];
          
          $cron_array  = $connection->createCommand("SELECT * FROM `merchant_db` WHERE `app_name` LIKE :app_name AND `merchant_id` NOT IN (:merchant)")
             ->bindValues($params)
             ->queryAll();
        }




        if(is_array($cron_array) && count($cron_array))
        {
            foreach($cron_array as $key=>$merchant)
            {
                try
                {
                    $merchant_id = $merchant['merchant_id'];

                    if(!in_array($merchant['db_name'], $dbList) || $merchant_id=='810') {
                        unset($cron_array[$key]);
                        continue;
                    }

                    if($processedMerchantCount == $size)
                        break;
                    $processedMerchantCount++;
                    unset($cron_array[$key]);

                    Yii::$app->dbConnection = Yii::$app->get($merchant['db_name']);
                    Yii::$app->merchant_id = $merchant_id;

                    Yii::$app->shop_name = $merchant['shop_name'];

                    //check already saved file
                    if (file_exists(\Yii::getAlias('@webroot').'/var/walmart/productpricing')) {
                        $files = glob(\Yii::getAlias('@webroot').'/var/walmart/productpricing/*'); // get all file names
                        if(!empty($files)){
                            foreach($files as $file){ // iterate files
                               $merchant_id = basename($file);
                               $contentFiles = glob($file.'/*');
                               if(!empty($contentFiles)){
                                    foreach ($contentFiles as $key => $value) {
                                      $data = file_get_contents($value);
                                      $status = WalmartrepricingController::postPricedata($data, $merchant_id);
                                      unlink($file);// delete file
                                    }
                               }
                            }
                        }
                    }
                    $cron_array = array();
                    $connection = Yii::$app->getDb();
                    $cronData = WalmartCronSchedule::find()->where(['cron_name'=>'product_repricing'])->one();
                    
                  /*  if($cronData && $cronData['cron_data'] != "") {

                        $query = "SELECT * FROM `walmart_product_repricing` WHERE `repricing_status`='1' AND `id`>'".$cronData['cron_data']."'ORDER BY `id` ASC LIMIT 100000";
                        $cron_array = Data::sqlRecords($query,'all','select');
                       if(empty($cron_array)){
                        $query = "SELECT * FROM `walmart_product_repricing` WHERE `repricing_status`='1' ORDER BY `id` ASC LIMIT 100000";
                        $cron_array = Data::sqlRecords($query,'all','select');
                       }
                    }
                    else {*/
                        $query = "SELECT * FROM `walmart_product_repricing` WHERE `repricing_status`='1' AND `merchant_id` ='{$merchant_id}' ORDER BY `id` ASC LIMIT 100000";
                        $cron_array = Data::sqlRecords($query,'all','select');

                   /* }*/

                    if(is_array($cron_array) && count($cron_array)>0)
                    {
                        $obj = new WalmartrepricingController(Yii::$app->controller->id,''); 
                        $obj->actionCronPriceUpdate($cron_array);
                    }


                }
                catch (Exception $e)
                {
                    Data::createLog("product status exception ".$e->getTraceAsString(),'productInventory/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\IntegrityException $e)
                {
                    Data::createLog("order fetch db-integrity-exception ".$e->getTraceAsString(),'productInventory/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\Exception $e)
                {
                    Data::createLog("order fetch db-exception ".$e->getTraceAsString(),'productInventory/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
            }
        }
    }

    /**
     * Shopify order shipment cron
     *
    */
    public function actionShopifyShipment()
    {
        ob_start ();

        $connection = Yii::$app->get(Yii::$app->getBaseDb());

        $dbList = Yii::$app->getDbList();
    
        $processedMerchantCount = 0;
        $size = 100;
        
        $result = WalmartCronSchedule::find()->where(['cron_name'=>'ship_order'])->one();
        if($result && $result['cron_data'] != "") {
            $cron_array = json_decode($result['cron_data'], true);
        }
        else {
            $cron_array = $connection->createCommand("SELECT * FROM `merchant_db` WHERE `app_name` LIKE '%".Data::APP_NAME_WALMART."%'")->queryAll();
        }
        if(is_array($cron_array) && count($cron_array))
        {
            $data = implode(',', array_keys($cron_array));
            Data::createCronstatuslog('ship',$data);
            foreach($cron_array as $key=>$merchant)
            {
                try
                {
                    if(!in_array($merchant['db_name'], $dbList)) {
                        unset($cron_array[$key]);
                        continue;
                    }

                    if($processedMerchantCount == $size)
                        break;
                    $processedMerchantCount++;
                    unset($cron_array[$key]);

                    Yii::$app->dbConnection = Yii::$app->get($merchant['db_name']);
                    Yii::$app->merchant_id = $merchant['merchant_id'];
                    Yii::$app->shop_name = $merchant['shop_name'];

                    $query = "SELECT `config`.`consumer_id`, `config`.`secret_key`, `shop`.`token`, `shop`.`email`, `shop`.`status` FROM `walmart_configuration` `config` INNER JOIN (SELECT `token`, `email`, `status`, `merchant_id` FROM `walmart_shop_details` WHERE `merchant_id`='{$merchant['merchant_id']}') `shop` ON `shop`.`merchant_id` = `config`.`merchant_id` WHERE `config`.`merchant_id`='{$merchant['merchant_id']}'";
                    $configData = Data::sqlRecords($query, 'one');

                    if($configData)
                    {
                        $isValidate = Walmartappdetails::isValidateapp($merchant['merchant_id']);

                        if(!$configData['status'] || $isValidate=="expire" || $isValidate=="trial_expired") {
                            continue;
                        }

                        $configData['merchant_id'] = $merchant['merchant_id'];
                        $configData['shop_url'] = $merchant['shop_name'];

                        $obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
                        $obj->actionSyncShipmentData($configData);
                    }
                }
                catch (Exception $e)
                {
                    Data::createLog("order ship exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderShipCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\IntegrityException $e)
                {
                    Data::createLog("order ship db-integrity-exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderShipCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\Exception $e)
                {
                    Data::createLog("order fetch db-exception ".$e->getMessage().PHP_EOL.$e->getTraceAsString(),'walmartOrderShipCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
            }
        }

        if(count($cron_array)==0)
          $result->cron_data="";
        else
          $result->cron_data=json_encode($cron_array);

        $result->save(false);
        $html = ob_get_clean();
    }
    /**
     * walmart order sync 
     *
    */
    public function actionSyncOrderStatus()
    {
        ob_start ();

        $connection = Yii::$app->get(Yii::$app->getBaseDb());

        $dbList = Yii::$app->getDbList();
    
        $processedMerchantCount = 0;
        $size = 100;
        
        $result = WalmartCronSchedule::find()->where(['cron_name'=>'walmart_order_sync'])->one();
        if($result && $result['cron_data'] != "") {
            $cron_array = json_decode($result['cron_data'], true);
        }
        else {
            $cron_array = $connection->createCommand("SELECT * FROM `merchant_db` WHERE `app_name` LIKE '%".Data::APP_NAME_WALMART."%'")->queryAll();
        }
        if(is_array($cron_array) && count($cron_array))
        {
            $data = implode(',', array_keys($cron_array));
            Data::createCronstatuslog('walmartordersync',$data);
            foreach($cron_array as $key=>$merchant)
            {
                try
                {
                    if(!in_array($merchant['db_name'], $dbList)) {
                        unset($cron_array[$key]);
                        continue;
                    }

                    if($processedMerchantCount == $size)
                        break;
                    $processedMerchantCount++;
                    unset($cron_array[$key]);

                    Yii::$app->dbConnection = Yii::$app->get($merchant['db_name']);
                    Yii::$app->merchant_id = $merchant['merchant_id'];
                    Yii::$app->shop_name = $merchant['shop_name'];

                    $query = "SELECT `config`.`consumer_id`, `config`.`secret_key`, `shop`.`token`, `shop`.`email`, `shop`.`status` FROM `walmart_configuration` `config` INNER JOIN (SELECT `token`, `email`, `status`, `merchant_id` FROM `walmart_shop_details` WHERE `merchant_id`='{$merchant['merchant_id']}') `shop` ON `shop`.`merchant_id` = `config`.`merchant_id` WHERE `config`.`merchant_id`='{$merchant['merchant_id']}'";
                    $configData = Data::sqlRecords($query, 'one');

                    if($configData)
                    {
                        $isValidate = Walmartappdetails::isValidateapp($merchant['merchant_id']);

                        if(!$configData['status'] || $isValidate=="expire" || $isValidate=="trial_expired") {
                            continue;
                        }

                        $configData['merchant_id'] = $merchant['merchant_id'];
                        $configData['shop_url'] = $merchant['shop_name'];

                        $obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
                        $obj->actionOrderStatus($configData);
                    }
                }
                catch (Exception $e)
                {
                    Data::createLog("walmartOrderSyncCron exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\IntegrityException $e)
                {
                    Data::createLog("walmartOrderSyncCron db-integrity-exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\Exception $e)
                {
                    Data::createLog("walmartOrderSyncCron db-exception ".$e->getTraceAsString(),'walmartOrderSyncCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
            }
        }

        if(count($cron_array)==0)
          $result->cron_data="";
        else
          $result->cron_data=json_encode($cron_array);

        $result->save(false);
        $html = ob_get_clean();
    }
    /**
     * Walmart Order Cancel
     *
    */
    public function actionCancelOrder()
    {
        ob_start ();

        $connection = Yii::$app->get(Yii::$app->getBaseDb());

        $dbList = Yii::$app->getDbList();
    
        $processedMerchantCount = 0;
        $size = 100;
        
        $result = WalmartCronSchedule::find()->where(['cron_name'=>'order_cancel'])->one();
        if($result && $result['cron_data'] != "") {
            $cron_array = json_decode($result['cron_data'], true);
        }
        else {
            $cron_array = $connection->createCommand("SELECT * FROM `merchant_db` WHERE `app_name` LIKE '%".Data::APP_NAME_WALMART."%'")->queryAll();
        }
        if(is_array($cron_array) && count($cron_array))
        {
            foreach($cron_array as $key=>$merchant)
            {
                try
                {
                    if(!in_array($merchant['db_name'], $dbList)) {
                        unset($cron_array[$key]);
                        continue;
                    }

                    if($processedMerchantCount == $size)
                        break;

                    $processedMerchantCount++;
                    unset($cron_array[$key]);

                    Yii::$app->dbConnection = Yii::$app->get($merchant['db_name']);
                    Yii::$app->merchant_id = $merchant['merchant_id'];
                    Yii::$app->shop_name = $merchant['shop_name'];

                    $query = "SELECT `config`.`consumer_id`, `config`.`secret_key`, `shop`.`token`, `shop`.`email`, `shop`.`status` FROM `walmart_configuration` `config` INNER JOIN (SELECT `token`, `email`, `status`, `merchant_id` FROM `walmart_shop_details` WHERE `merchant_id`='{$merchant['merchant_id']}') `shop` ON `shop`.`merchant_id` = `config`.`merchant_id` WHERE `config`.`merchant_id`='{$merchant['merchant_id']}'";
                    $configData = Data::sqlRecords($query, 'one');

                    if($configData)
                    {
                        $isValidate = Walmartappdetails::isValidateapp($merchant['merchant_id']);

                        if(!$configData['status'] || $isValidate=="expire" || $isValidate=="trial_expired") {
                            continue;
                        }

                        $configData['merchant_id'] = $merchant['merchant_id'];
                        $configData['shop_url'] = $merchant['shop_name'];

                        $obj = new WalmartorderdetailController(Yii::$app->controller->id,'');
                        $obj->actionCancelOrderMgmt($configData);
                    }
                }
                catch (Exception $e)
                {
                    Data::createLog("order Cancel exception ".$e->getTraceAsString(),'walmartOrderCancelCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\IntegrityException $e)
                {
                    Data::createLog("order Cancel db-integrity-exception ".$e->getTraceAsString(),'walmartOrderCancelCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
                catch(\yii\db\Exception $e)
                {
                    Data::createLog("order Cancel db-exception ".$e->getTraceAsString(),'walmartOrderCancelCron/exception.log','a',true);
                    if(isset($cron_array[$key]))
                        unset($cron_array[$key]);
                    continue;
                }
            }
        }

        if(count($cron_array)==0)
          $result->cron_data="";
        else
          $result->cron_data=json_encode($cron_array);

        $result->save(false);
        $html = ob_get_clean();
    }

    public function actionSyncWalmartProduct()
    {
        $processedMerchantCount = 0;
        $size = 1;

        $result = WalmartCronSchedule::find()->where(['cron_name' => 'sync_product'])->one();
        if ($result && $result['cron_data'] != "") {

            $cron_array = json_decode($result['cron_data'], true);

        } else {

            $query = "SELECT walmart_extension_detail.merchant_id,merchant_db.db_name FROM walmart_extension_detail INNER JOIN merchant_db ON walmart_extension_detail.merchant_id=merchant_db.merchant_id WHERE walmart_extension_detail.`status` = 'Purchased' AND walmart_extension_detail.`app_status` LIKE 'install'";

            $cron_array = QueryHelper::sqlRecords($query, null, 'all');
        }

        if (is_array($cron_array) && count($cron_array)) {
            foreach ($cron_array as $key => $merchant) {

                if ($processedMerchantCount == $size)
                    break;

                $processedMerchantCount++;

                $shopdata = Data::getWalmartShopDetails($merchant['merchant_id']);
                $sc = new ShopifyClientHelper($shopdata['shop_url'], $shopdata['token'], WALMART_APP_KEY, WALMART_APP_SECRET);

                $walmartProductSync = new WalmartProductSync($merchant['merchant_id'], $merchant['db_name'], $sc);

                $walmartProductSync->insertProcessedMerchant();
                $response = $walmartProductSync->execute();
                if(!$response)
                {
                    $walmartProductSync->unsetSyncMerchant();
                    unset($cron_array[$key]);
                }
            }
        }

        if (count($cron_array) == 0)
            $result->cron_data = "";
        else
            $result->cron_data = json_encode($cron_array);

        $result->save(false);

    }
}
