<?php

namespace console\controllers;

use frontend\modules\sears\controllers\SearscronController as Shopifysears;
use frontend\modules\sears\controllers\SearsinvsyncController;
use Yii;
use yii\console\Controller;

/**
 * Cron controller
 */
class SearsCronController extends Controller
{
    public function actionIndex()
    {
        // echo "cron service runnning";
        echo getcwd();
    }

    // Update product qty on sears
    public function actionSearsinvupdate()
    {
        $obj = new Shopifysears(Yii::$app->controller->id, '');
        $obj->actionQtysync(true);
    }

    // Update product price on sears
    public function actionSearspriceupdate()
    {
        $obj = new Shopifysears(Yii::$app->controller->id, '');
        $obj->actionPricesync(true);
    }

    // Fetch new Orders from Sears
    public function actionSearsorderfetch()
    {
        $obj = new Shopifysears(Yii::$app->controller->id, '');
        $obj->actionFetchsearsorder(true);
    }

    // Order shipment cron (from shopify to sears)
    public function actionSearsordership()
    {
        $obj = new Shopifysears(Yii::$app->controller->id, '');
        $obj->actionShipsearsorder(true);
    }

    /*
        public function actionSyncsearsorder()
        {
          $obj = new Shopifysears(Yii::$app->controller->id,'');
          $obj->actionSyncorder(true);
        }
    */
}
