<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use frontend\modules\walmart\controllers\BulkUploadServerController;

/**
* Cron controller
*/
class BulkUploadController extends Controller 
{
	public function actionStart() 
	{
		$server = new BulkUploadServerController(Yii::$app->controller->id,'');
		$server->actionStart();
	}
}