<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

class MainController extends Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if(Yii::$app->user->isGuest) {
                Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl);
                return false;
            } else {
                return true;   
            }
        } else {
            return false;
        }
    }
}
