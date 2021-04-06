<?php
namespace maintenance\controllers;

use Yii;
use common\models\AppStatus;
use common\models\JetExtensionDetail;
use common\models\LoginForm;
use common\models\Post;
use common\models\User;

use frontend\components\Jetappdetails;
use frontend\components\Sendmail;
use frontend\components\Data;
use frontend\components\ShopifyClientHelper;
use frontend\components\Shopifyinfo;
use frontend\components\Createwebhook;
use frontend\components\Jetproductinfo;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
	protected $shop;
	protected $token;
	protected $connection;
	protected $merchant_id;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                         'roles' => ['?'],
                    ],
                	[
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {

        return [
            'error' => [
                'class' => 'maintenance\controllers\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	public function actionError()
	{
        
		// die('ghfhfh');
	    $exception = Yii::$app->errorHandler->exception;
	    $error=Yii::$app->errorHandler->error;
	    if ($exception !== null) {
	        return $this->render('error', ['exception' => $exception, 'error'=>$error]);
	    }
	}
}
