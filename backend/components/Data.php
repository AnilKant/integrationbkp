<?php 
namespace backend\components;
use Yii;
use yii\base\Component;
use yii\helpers\Url;

class Data extends component
{
    public static function sqlRecords($query, $type = null, $queryType = null, $dbName = null)
    {
        // $connection = Yii::$app->getDb();
        if(is_null($dbName))
            $connection = Yii::$app->getDb();
        else
            $connection = Yii::$app->get($dbName);
        $response = [];
        if ($queryType == "update" || $queryType == "delete" || $queryType == "insert" || ($queryType == null && $type == null)) {
            $response = $connection->createCommand($query)->execute();
        } elseif ($type == 'one') {
            $response = $connection->createCommand($query)->queryOne();
        } elseif ($type == 'column') {
            $response = $connection->createCommand($query)->queryColumn();
        } else {
            $response = $connection->createCommand($query)->queryAll();
        }
        unset($connection);
        return $response;
    }

    public static function integrationSqlRecords($query,$type=null,$queryType=null)
    {
        $connection=Yii::$app->admin;
        $response=[];
        if($queryType=="update" || $queryType=="delete" || $queryType=="insert"){
            $response=$connection->createCommand($query)->execute();
        }
        elseif($type=='one'){
            $response=$connection->createCommand($query)->queryOne();
        }
        else{
            $response=$connection->createCommand($query)->queryAll();
        }
        unset($connection);
        return $response;
    }

    public static function getWalmartShopDetails($merchant_id)
    {      
        $shopDetails = array();
        if(is_numeric($merchant_id)) {
            $shopDetails = self::sqlRecords("SELECT `token`,`currency`,`email`,`shop_name`,`shop_url` FROM `walmart_shop_details` WHERE merchant_id=".$merchant_id, 'one');
        }
        return $shopDetails;
    }

    public static function getUrl($path)
    {
        $url = Url::toRoute([$path]);
        return $url;
    }

    public static function createWebhooks($sc)
    {
        $hostname = Yii::getAlias('@hostname').'/';
        $response=$sc->call('GET','/admin/webhooks.json');
        $charge=array();
        $arr=array();
        $charge1=array();
        $charge2=array();
        $charge3=array();
        $charge4=array();
        $charge5=array();
        $charge6=array();
        $arr1=array();
        $arr2=array();
        $arr3=array();
        $arr4=array();
        $arr5=array();
        $arr['topic']="products/update";
        $arr1['topic']="products/delete";
        $arr2['topic']="app/uninstalled";
        $arr3['topic']="orders/fulfilled";
        $arr4['topic']="orders/cancelled";
        $arr5['topic']="products/create";
        $return_url= $hostname."jet/shopifywebhook/productupdate";
        $return_url1= $hostname."jet/shopifywebhook/productdelete";
        $return_url2= $hostname."jet/shopifywebhook/isinstall";
        $return_url3= $hostname."jet/shopifywebhook/createshipment";
        $return_url4= $hostname."jet/shopifywebhook/cancelled";
        $return_url5= $hostname."jet/shopifywebhook/productcreate";
        $arr['address']=$return_url;
        $arr1['address']=$return_url1;
        $arr2['address']=$return_url2;
        $arr3['address']=$return_url3;
        $arr4['address']=$return_url4;
        $arr5['address']=$return_url5;
        $charge['webhook']=$arr;
        $charge1['webhook']=$arr1;
        $charge2['webhook']=$arr2;
        $charge3['webhook']=$arr3;
        $charge4['webhook']=$arr4;
        $charge5['webhook']=$arr5;

        $flag=false;
        $flag1=false;
        $flag2=false;
        $flag3=false;
        $flag4=false; 
        $flag5=false;
        $flag6=false;
        if(count($response)>0 && !isset($response['errors']))
        {
            foreach ($response as $value)
            {
                if(isset($value['address']) && $value['address']==$hostname."jet/shopifywebhook/productupdate")
                {
                    $flag=true;
                }
                if(isset($value['address']) && $value['address']==$hostname."jet/shopifywebhook/productdelete")
                {
                    $flag1=true;
                }
                if(isset($value['address']) && $value['address']==$hostname."jet/shopifywebhook/isinstall")
                {
                    $flag2=true;
                }
                if(isset($value['address']) && $value['address']==$hostname."jet/shopifywebhook/createshipment")
                {
                    $flag3=true;
                }
                if(isset($value['address']) && $value['address']==$hostname."jet/shopifywebhook/cancelled")
                {
                    $flag4=true;
                }
                if(isset($value['address']) && $value['address']==$hostname."jet/shopifywebhook/productcreate")
                {
                    $flag5=true;
                }
            }
        }
        if(!$flag)
            $response1 = $sc->call('POST','/admin/webhooks.json',$charge);
        if(!$flag1)
            $response2 = $sc->call('POST','/admin/webhooks.json',$charge1);
        if(!$flag2)
            $response3 = $sc->call('POST','/admin/webhooks.json',$charge2);
        if(!$flag3)
            $response4 = $sc->call('POST','/admin/webhooks.json',$charge3);
         if(!$flag4)
            $response5 = $sc->call('POST','/admin/webhooks.json',$charge4);
         if(!$flag5)
            $response6 = $sc->call('POST','/admin/webhooks.json',$charge5);
        unset($arr);
        unset($arr1);
        unset($arr2);
        unset($arr3);
        unset($arr4);
        unset($arr5);
        unset($charge);
        unset($charge1);
        unset($charge2);
        unset($charge3);
        unset($charge4);
        unset($charge5);
    }

    public static function saveConfigValue($merchant_id, $field_name, $field_value)
    {
        $query = "SELECT `data`,`value` FROM  `walmart_config` WHERE `merchant_id`='".$merchant_id."' AND `data`='".$field_name."'";
        if (empty(self::sqlRecords($query,"one"))) 
        {
            self::sqlRecords("INSERT INTO `walmart_config` (`data`,`value`,`merchant_id`) values('".$field_name."','".$field_value."','".$merchant_id."')", null, "insert");
        } 
        else 
        {
            self::sqlRecords("UPDATE `walmart_config` SET `value`='".$field_value."' where `merchant_id`='".$merchant_id."' AND `data`='".$field_name."'", null, "update");
        }
    }

    public static function jetsaveConfigValue($merchant_id, $field_name, $field_value)
    {
        $query = "SELECT `data`,`value` FROM  `jet_config` WHERE `merchant_id`='".$merchant_id."' AND `data`='".$field_name."'";
        if (empty(self::sqlRecords($query,"one"))) 
        {
            self::sqlRecords("INSERT INTO `jet_config` (`data`,`value`,`merchant_id`) values('".$field_name."','".$field_value."','".$merchant_id."')", null, "insert");
        } 
        else 
        {
            self::sqlRecords("UPDATE `jet_config` SET `value`='".$field_value."' where `merchant_id`='".$merchant_id."' AND `data`='".$field_name."'", null, "update");
        }
    }

    public function getShopifyShopDetails($sc)
    {
        $response = $sc->call('GET','/admin/shop.json');
        return $response;
    }

    /**
     * Get Product Tax code
     * @param [] $product
     * @return string | bool
     */
    public static function GetTaxCode($product, $merchant_id)
    {
        $tax_code = '';
        $productType = '';
        if(is_array($product)) {
            $tax_code = $product['tax_code'];
            $productType = $product['product_type'];
        }
        else {
            $tax_code = $product->tax_code;
            $productType = $product->product_type;
        }

        if(!$tax_code) {
            $query = "SELECT `tax_code` FROM `walmart_category_map` WHERE `product_type`='".$productType."' AND `merchant_id`=".$merchant_id." LIMIT 0,1";
            $result = Data::sqlRecords($query, 'one');
            if($result && (isset($result['tax_code']))) {
                return $result['tax_code'];
            }
            else {
                $query = "SELECT `value` FROM `walmart_config` WHERE `data`='tax_code' AND `merchant_id`=".$merchant_id." LIMIT 0,1";
                $result = Data::sqlRecords($query, 'one');
                if($result && (isset($result['value']))) {
                    return $result['value'];
                }
            }
        } else {
            if(!is_numeric($tax_code))
                return false;
            else
                return $tax_code;
        }
        return false;
    }

    public static function createLog($message,$path='jet-common.log',$mode='a',$sendMail=false)
    {
        $file_path=Yii::getAlias('@webroot').'/var/'.$path;
        $dir = dirname($file_path);
        if (!file_exists($dir)){
            mkdir($dir,0775, true);
        }
        $fileOrig=fopen($file_path,$mode);
        fwrite($fileOrig,"\n".date('d-m-Y H:i:s')."\n".$message);
        fclose($fileOrig); 
        if($sendMail){
            self::sendEmail($file_path,$message);
        }
    }

    public static function createFile($path='jet-common.log',$mode='a')
    {
        $file_path=Yii::getAlias('@webroot').'/var/'.$path;
        $dir = dirname($file_path);
        if (!file_exists($dir)){
            mkdir($dir,0775, true);
        }
        $fileOrig=fopen($file_path,$mode);
        return $fileOrig;
    }

    public static function getKey($string){
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    /**
     * function for sending mail with attachment
     */
    public static function sendEmail($file,$msg,$email = 'satyaprakash@cedcoss.com')
    {
       try
       {
            $name = 'Walmart Shopify Cedcommerce';
        
            $EmailTo = $email.',amitkumar@cedcoss.com';
            $EmailFrom = $email;
            $EmailSubject = "Walmart Shopify Cedcommerce Exception Log" ;
            $from ='Walmart Shopify Cedcommerce';
            $message = $msg;
            $separator = md5(time());

            // carriage return type (we use a PHP end of line constant)
            $eol = PHP_EOL;

            // attachment name
            $filename = 'exception';//store that zip file in ur root directory
            $attachment = chunk_split(base64_encode(file_get_contents($file)));

            // main header
            $headers  = "From: ".$from.$eol;
            $headers .= "MIME-Version: 1.0".$eol; 
            $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

            // no more headers after this, we start the body! //

            $body = "--".$separator.$eol;
            $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol.$eol;
            $body .= $message.$eol;

            // message
            $body .= "--".$separator.$eol;
            /*  $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
            $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
            $body .= $message.$eol; */

            // attachment
            $body .= "--".$separator.$eol;
            $body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
            $body .= "Content-Transfer-Encoding: base64".$eol;
            $body .= "Content-Disposition: attachment".$eol.$eol;
            $body .= $attachment.$eol;
            $body .= "--".$separator."--";

            // send message
            if (mail($EmailTo, $EmailSubject, $body, $headers)) {
                $mail_sent = true;
            } else {
                $mail_sent = false;
            }
        }
        catch(Exception $e)
        {
            
        }
    }

    /**
     * Get Option Values Simple Product
     */
    public function getOptionValuesForSimpleProduct($product)
    {
        $options = [];
       
        $variant = reset($product['variants']);
        if(isset($product['options'])) {
            foreach ($product['options'] as $value) {
                if($value['name'] != 'Title') {
                    $options[$value['name']] = $variant['option'.$value['position']];
                }
            }
        }
        if(count($options))
            return json_encode($options);
        else
            return '';
    }

    public static function sendCurlRequest($data = [], $url = "")
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            //curl_setopt($ch, CURLOPT_TIMEOUT,1);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;    //return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function removeconfigfile($shopurl,$moduleName,$oldOrNewApp){
        if($oldOrNewApp == 'old'){
            $base_path = '/home/shopif8/public_html/shopify/integration/frontend/modules/'.$moduleName.'/config/'.$shopurl;
        }else{
            $base_path = '/home/shopif8/public_html/shopify/marketplace-integration/frontend/modules/'.$moduleName.'/config/'.$shopurl;
        }
        if (file_exists($base_path)) {
            unlink($base_path.'/'.'config.php');
        }
        if(is_dir($base_path)){
            rmdir($base_path);
        }
        return true;
    }
}
?>
