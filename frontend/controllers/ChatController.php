<?php
namespace frontend\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\modules\walmart\controllers\WalmartmainController;
use frontend\modules\walmart\components\Data;

/**
* Chat controller
*/
class ChatController extends WalmartmainController 
{
	public function actionBox()
  	{
  		$merchant_id = Yii::$app->user->identity->id;
  		
  		$data = ['sender_ref'=>$merchant_id,
  				 'sender_type'=>'customer',
  				 'sender_name'=>$this->getMerchantName($merchant_id),
  				 'reciever_type'=>'group',
  				 'group_id'=>'shopify_walmart_team'

  				];


  		$hash = $this->getHash($data);
  		$this->setHash($hash,$data);
  		
  		$this->layout="blank";
    	$html=$this->render('box',['hash'=>$hash]);
    	return $html; 
  	}

  	public function getMerchantName($id){
  		$merchant = Data::sqlRecords("SELECT name FROM jet_registration WHERE merchant_id='{$id}'",null,'one');
  		return $merchant[0]['name'];
  	}
  	public function setHash($hash,$data){
  		$directory = \Yii::getAlias('@webroot').'/frontend/view/chat/hash';
  		if(file_exists($directory.'/'.$hash)){
  			if($data['sender_type']=='customer'){
  				Data::sqlRecords('UPDATE client_chat SET active=1 WHERE merchant_id='.$data['sender_ref'],null,'update');
  			}
  			return;
  		}
  		if($data['sender_type']=='customer'){
	  		$conversation = Data::sqlRecords('select conversation from client_chat where merchant_id='.$data['sender_ref'],null,'one');
	  		if($conversation){
	  			Data::sqlRecords('UPDATE client_chat SET active=1 WHERE merchant_id='.$data['sender_ref'],null,'update');
	  		}
	  		else
	  		{
	  			$conversation = addslashes(json_encode([]));
				Data::sqlRecords("insert into client_chat (merchant_id,conversation,active) values({$data['sender_ref']},'{$conversation}','1')",null,'insert');
	  		}
	  	}
        if (!file_exists($directory)){
            mkdir($directory,0775, true);
        }

        $handle = fopen($directory.'/'.$hash,'a');
        fwrite($handle,json_encode($data));
        fclose($handle);
  	}
  	public function actionSupport()
  	{
  		$merchant_id = Yii::$app->user->identity->id;
  		
  		$data = ['group_id'=>'shopify_walmart_team',
  				 'sender_type'=>'group',
  				 'sender_ref'=>'group',
  				 'sender_name'=>'Cedcommerce Support',
  				 'reciever_type'=>'customer',
  				 'reciever_ref'=>'14'

  				];
  		$hash = $this->getHash($data);
  		$this->setHash($hash,$data);

  		$activeMerchants = Data::sqlRecords('SELECT * FROM client_chat WHERE active="1"');
  		$this->layout="blank";
    	$html=$this->render('support',['hash'=>$hash,'activeMerchants'=>$activeMerchants]);
    	return $html; 
  	}

  	public function getHash($data)
  	{
  		$finalString = '';
  		foreach($data as $val){
  			$finalString .= $val;
  		}
  		return md5($finalString);
  	}

  	public function actionLoad(){
  		$merchant_id = Yii::$app->user->identity->id;
  		$conversation = Data::sqlRecords('select conversation from client_chat where merchant_id='.$merchant_id,null,'one');
  		$conversation = json_decode($conversation[0]['conversation'],true);
  		$html = '';

  		foreach($conversation as $data){
  			$html .= '<li><span class="user-name">'.$data['sender_name'].'</span> : <span>'.$data['message'].'</span></li>';
  		}

  		echo $html;die;

  	}
  	public function actionClear(){
  		$conversation = addslashes(json_encode([]));
  		$merchant_id = Yii::$app->user->identity->id;
  		Data::sqlRecords("UPDATE client_chat SET conversation='{$conversation}' WHERE merchant_id=".$merchant_id,null,'update');
  		die;
  	}
}