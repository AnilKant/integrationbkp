<?php
namespace frontend\components;

use Yii;
use yii\base\Component;

class Data extends component
{
    public static function sqlRecords($query,$type=null,$queryType=null,$dbName=null)
    {
        if(is_null($dbName))
            $connection = Yii::$app->getDb();
        else
            $connection = Yii::$app->get($dbName);

        $response=[];
        if($queryType=="update" || $queryType=="delete" || $queryType=="insert" || ($queryType==null && $type==null))           
               $response= $connection->createCommand($query)->execute();                   
        elseif($type=='one')
            $response=$connection->createCommand($query)->queryOne();        
        else
            $response=$connection->createCommand($query)->queryAll();
        
        unset($connection);
        return $response;
    }

    public static function checkInstalledApp($merchant_id,$type=false,&$installData=[])
    {
        $installInfo = $jetData = [];
        $jetData = self::sqlRecords("SELECT `auth_key` FROM `user` WHERE id='".$merchant_id."' LIMIT 0,1","one","select");
        if(isset($jetData['auth_key']) && ($jetData['auth_key']!="") )
        {
            $installInfo['jet']['url']=Yii::getAlias('@weburl');
            if($type)
               $installInfo['jet']['type']="Switch";
        }
        else
        {
            $installInfo['jet']['url']='https://apps.shopify.com/jet-integration';
            if($type)
               $installInfo['jet']['type']="Install";
        }
        $walmartData=self::sqlRecords("SELECT `id` FROM `walmart_shop_details` WHERE `merchant_id`='".$merchant_id."' LIMIT 0,1","one","select");
        if(isset($walmartData['id']))
        {
            $installData['walmart']=true;
            $installInfo['walmart']['url']=Yii::getAlias('@webwalmarturl');
            if($type)
               $installInfo['walmart']['type']="Switch"; 
        }
        else
        {
            $installInfo['walmart']['url']='https://apps.shopify.com/walmart-marketplace-integration';
            if($type)
               $installInfo['walmart']['type']="Install"; 
        }
        $neweggData=self::sqlRecords("SELECT `id` FROM `newegg_shop_detail` WHERE `merchant_id`='".$merchant_id."' LIMIT 0,1","one","select");
        if(isset($neweggData['id']))
        {
            $installData['newegg']=true;
            $installInfo['newegg']['url']=Yii::getAlias('@webneweggurl');
            if($type)
               $installInfo['newegg']['type']="Switch"; 
        }
        else
        {
            $installInfo['newegg']['url']='https://apps.shopify.com/newegg-marketplace-integration';
            if($type)
               $installInfo['newegg']['type']="Install"; 
        }
        $searsData=self::sqlRecords("SELECT `id` FROM `sears_shop_details` WHERE `merchant_id`='".$merchant_id."' LIMIT 0,1","one","select");
        if(isset($searsData['id']))
        {
            $installData['sears']=true;
            $installInfo['sears']['url']=Yii::getAlias('@websearsurl');
            if($type)
               $installInfo['sears']['type']="Switch"; 
        }
        else
        {
            $installInfo['sears']['url']='https://apps.shopify.com/sears-marketplace-integration';
            if($type)
               $installInfo['sears']['type']="Install"; 
        }
        return $installInfo;
    }

    public static function sendCurlRequest($data=[],$url="")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $data ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_TIMEOUT,1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
    public static function createLog($message, $path = 'Referral.log', $mode = 'a', $sendMail = false,$trace = false)
    {
    	$file_path = Yii::getAlias('@webroot') . '/var/Referral/' . $path;
    	$dir = dirname($file_path);
    	if (!file_exists($dir)) {
    		mkdir($dir, 0775, true);
    	}
    	$fileOrig = fopen($file_path, $mode);
    	if($trace){
    		try{
    			throw new \Exception($message);
    		}
    		catch(Exception $e){
    			$message = $e->getTraceAsString();
    		}
    	}
    	fwrite($fileOrig, "\n" . date('d-m-Y H:i:s') . "\n" . $message);
    	fclose($fileOrig);
    	if ($sendMail) {
    
    		self::sendEmail($file_path, $message);
    	}
    }
    
    /**
     * function for sending mail with attachment
     */
    public static function sendEmail($file, $msg, $email = 'satyaprakash@cedcoss.com', $EmailSubject="Sears Shopify Cedcommerce Exception Log")
    {
    	try {
    		$name = 'Referral Shopify Cedcommerce';
    
    		$EmailTo = $email . ',kshitijverma@cedcoss.com';
    		$EmailFrom = $email;
    		$from = 'shopify@cedcommerce.com';
    		$message = $msg;
    		$separator = md5(time());
    
    		// carriage return type (we use a PHP end of line constant)
    		$eol = PHP_EOL;
    
    		// attachment name
    		$filename = 'exception';//store that zip file in ur root directory
    		$attachment = chunk_split(base64_encode(file_get_contents($file)));
    
    		// main header
    		$headers = "From: " . $from . $eol;
    		$headers .= "MIME-Version: 1.0" . $eol;
    		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";
    
    		// no more headers after this, we start the body! //
    
    		$body = "--" . $separator . $eol;
    		$body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol . $eol;
    		$body .= $message . $eol;
    
    		// message
    		$body .= "--" . $separator . $eol;
    		/*  $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
    		 $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
    		 $body .= $message.$eol; */
    
    		// attachment
    		$body .= "--" . $separator . $eol;
    		$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
    		$body .= "Content-Transfer-Encoding: base64" . $eol;
    		$body .= "Content-Disposition: attachment" . $eol . $eol;
    		$body .= $attachment . $eol;
    		$body .= "--" . $separator . "--";
    
    		// send message
    		if (mail($EmailTo, $EmailSubject, $body, $headers)) {
    			$mail_sent = true;
    		} else {
    			$mail_sent = false;
    		}
    	} catch (Exception $e) {
    
    	}
    }

    public static function getMerchantIdFromShop($shop_name)
    {
        $query = "SELECT `id` FROM `user` WHERE `username` LIKE '{$shop_name}' LIMIT 0,1";
        $data = self::sqlRecords($query, 'one', 'select');

        if ($data) {
            return $data['id'];
        } else {
            return false;
        }
    }

    
}
?>