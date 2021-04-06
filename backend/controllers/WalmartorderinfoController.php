<?php

namespace backend\controllers;

use backend\components\Data;
use backend\models\WalmartOrderDetailSearch;
use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;


/**
 * Walmart Order detail Controller
 */
class WalmartorderdetailsController extends MainController
{

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }

        $searchModel = new WalmartOrderDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*public function actionIndex()
    {
        $searchModel = new WalmartOrderDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

}