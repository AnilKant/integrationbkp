<?php
namespace frontend\controllers;
use frontend\modules\walmart\components\Data;

use yii\helpers\BaseJson;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;


/**
 * Site controller
 */
class RabbitmqController extends Controller
{
    const URLS = [
                    'walmart' => [
                                'processOrder' => '/walmart/walmartorderdetail/index',
                                'processInventory' => '/walmart/inventoryUpdate/index',
                                'validateMerchant' => '\frontend\modules\walmart\components\Data',
                                 ],
                ];

    public function actionListMerchants()
    {
        $work = $_POST['work'] ?? 'URLS';
        $workconstant = constant('self::'. $work);
        $data = [];
        $allMerchant = Data::sqlRecords("SELECT * FROM `merchant_db`",null,'select');
        foreach ($allMerchant as $merchant) {
            foreach ($workconstant as $key => $value) {
                if(isset($value['processOrder']))
                {
                    $data['urls'][$key]['processOrder'] = $value['processOrder'];
                }
                if(isset($value['processInventory'])){
                    $data['urls'][$key]['processInventory'] = $value['processInventory'];
                }
                
                if($value['validateMerchant']::getValidMerchant($merchant['merchant_id'],$merchant['db_name']))
                {
                    $data['data'][$merchant['merchant_id']][$key] = $value['validateMerchant']::getValidMerchant($merchant['merchant_id'],$merchant['db_name']);
                }
                
                
            }
        }
        $returnData = [];
        $data = ['merchants' => $data];
        $publicKey = file_get_contents(Yii::getAlias('@webroot').'/secure/mykey.pub');
        $crypttext = '';
        $text = json_encode($data);
        $maxlength=117;
        $output='';
        $source = $text;
        while($source){
          $input= substr($source,0,$maxlength);
          $source=substr($source,$maxlength);
          openssl_public_encrypt($input,$encrypted,$publicKey);
          
               
          $output.=$encrypted;
        }

        $returnData['data'] = base64_encode($output);
        return json_encode($returnData);

    }

}
