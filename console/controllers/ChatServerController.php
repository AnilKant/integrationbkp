<?php
namespace console\controllers;
use yii\console\Controller;
use Yii;
use yii\web;

//use frontend\controllers\ChatServerController as FrontendChatServerController;
use frontend\modules\walmart\controllers\ChatServerController as FrontendChatServerController;

/**
* Cron controller
*/
class ChatServerController extends Controller 
{

  public function actionStart() 
  {
     $server = new FrontendChatServerController(Yii::$app->controller->id,'');
     $server->actionIndex();
      
  }
}