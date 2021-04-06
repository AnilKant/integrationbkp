<?php

namespace backend\controllers;

use Yii;
use backend\models\WalmartClientDetails;
use backend\models\WalmartClientDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\walmart\controllers\WalmartmainController;
use frontend\modules\walmart\components\Data;
use backend\controllers\MainController;

/**
 * JetTestApiController implements the CRUD actions for JetTestApi model.
 */
class ChatController extends MainController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['support'],
                ],
            ],
        ];
    }
    /*public function beforeAction($action){
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        return true;
    }*/
   public function actionSupport()
    {
       
        
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
        $activeMerchants = is_array($activeMerchants)?$activeMerchants:[];
        $this->layout="main";
        $html=$this->render('support',['hash'=>$hash,'activeMerchants'=>$activeMerchants]);
        return $html; 
    }
    public function setHash($hash,$data){

         $directory = dirname(\Yii::getAlias('@webroot')).'/frontend/view/chat/hash';
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
    public function getHash($data)
    {
        $finalString = '';
        foreach($data as $val){
            $finalString .= $val;
        }
        return md5($finalString);
    }

    public function actionLoad(){
        $merchant_id = $_GET['id'];
        $conversation = Data::sqlRecords('select conversation from client_chat where merchant_id='.$merchant_id,null,'one');
        $conversation = json_decode($conversation[0]['conversation'],true);
        $html = '';

        foreach($conversation as $data){
            $html .= '<li>'.$data['user_id'].' : '.$data['message'].'</li>';
        }

        echo $html;die;

    }
    public function actionClear(){
        $conversation = addslashes(json_encode([]));
        $merchant_id = $_GET['id'];
        Data::sqlRecords("UPDATE client_chat SET conversation='{$conversation}' WHERE merchant_id=".$merchant_id,null,'update');
        die;
    }
}
