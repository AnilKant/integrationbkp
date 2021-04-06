<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\QueryHelper;
use frontend\components\ShopifyClientHelper;

class RecurringPaymentController extends MainController
{
    public static $app_list = [
        1 => 'walmart',
        2 => 'jet',
        3 => 'newegg',
        4 => 'sears',
        5 => 'neweggca',
        6 => 'bonanza',
        7 => 'tophatter',
        8 => 'pricefalls',
        9 => 'wish',
        10=> 'fruugo',
        11=> 'bestbuyca',
        12=> 'walmartca',
        13=> 'etsy'
    ];

    public static  $appData = [
            'walmart' => [
                'token_table'           => 'walmart_shop_details',
                'token_column'          => 'token',
                'merchant_id_column'    => 'merchant_id',
                'db_name'               => 'db'
            ],
            'jet' => [
                'token_table'           => '',
                'token_column'          => '',
                'merchant_id_column'    => '',
                'db_name'               => 'db'
            ],
            'newegg' => [
                'token_table'           => '',
                'token_column'          => '',
                'merchant_id_column'    => '',
                'db_name'               => 'db'
            ],
            'neweggcanada' => [
                'token_table'           => '',
                'token_column'          => '',
                'merchant_id_column'    => '',
                'db_name'               => 'db'
            ],
            'sears' => [
                'token_table'           => '',
                'token_column'          => '',
                'merchant_id_column'    => '',
                'db_name'               => 'db'
            ],
            'wish' => [
                'token_table'           => 'wish_shop_details',
                'token_column'          => 'token',
                'merchant_id_column'    => 'merchant_id',
                'db_name'               => 'admin'
            ]
        ];

    public function actionChooseApp()
    {
        $mid = Yii::$app->request->get('mid', false);
        
        if($mid) {
            return $this->render('choose-app', [
                'merchant_id' => $mid,
            ]);
        }
        else {
            die('invalid merchant_id');
        }
    }

    public function actionListRecurring()
    {
        $mid = Yii::$app->request->get('mid', false);
        $app = Yii::$app->request->get('app', false);
        if($mid && $app)
        {
            $sc = $this->getShopifyClientObj($mid, $app);

            if($sc)
            {
                $response = $sc->call('GET', '/admin/recurring_application_charges.json');
                
                if(!isset($response['errors']))
                {
                    return $this->render('list-recurring', [
                        'payments'    => $response,
                        'merchant_id' => $mid,
                        'app'         => $app
                    ]);
                }
                else
                {
                    $message = 'error from shopify : ' . $response['errors'];
                    echo $message;die;
                }
            }
            else
            {
                die('shopify object not created.');
            }
        }
        else {
            die('invalid merchant_id OR app');
        }
    }

    public function getShopifyClientObj($mid, $app)
    {
        if(isset(self::$app_list[$app], self::$appData[self::$app_list[$app]]))
        {
            $token_table = self::$appData[self::$app_list[$app]]['token_table'];
            $token_column = self::$appData[self::$app_list[$app]]['token_column'];
            $mid_column = self::$appData[self::$app_list[$app]]['merchant_id_column'];
            $db_name = self::$appData[self::$app_list[$app]]['db_name'];

            $query = "SELECT `m`.`shop_name` as `shop`, `mkt`.`{$token_column}` as `token` FROM `merchant_db` `m` INNER JOIN `{$token_table}` `mkt` ON `m`.`merchant_id`=`mkt`.`{$mid_column}` WHERE `m`.`merchant_id`=:mid";
            $data = QueryHelper::executeQuery($query, [':mid' => $mid], 'one', $db_name);
            
            if($data) {
                return new ShopifyClientHelper($data['shop'], $data['token'], null, null);
            }
        }

        return false;
    }

    /**
     * cancel recurring monthly payment 
     */
    public function actionCancelPayment()
    {
        $mid = Yii::$app->request->get('mid', false);
        $app = Yii::$app->request->get('app', false);
        $payment_id = Yii::$app->request->get('pid', false);

        if($mid && $app && $payment_id)
        {
            $sc = $this->getShopifyClientObj($mid, $app);
            if($sc)
            {
                $response = $sc->call('DELETE', '/admin/recurring_application_charges/'.$payment_id.'.json');
                if(!isset($response['errors'])) {
                    echo '<p><b>cancelled successfully</b></p><p><a href="list-recurring?mid='.$mid.'&app='.$app.'">Back</a></p>';
                    die;   
                } else {
                    var_dump($response);die;
                }
            }
            else
            {
                die('shopify object not created.');
            }
        }
        else {
            die('invalid merchant_id OR app OR payment_id');
        }                
    }
    
    /*public function actionViewpayment()
    {
        $this->layout="main2";
        $payment_id=$_POST['id'];
        $merchant_id=$_POST['merchant_id'];
        $planType=$_POST['type'];
        
        $connection=Yii::$app->getDb();
          
        $selectShop = $connection->createCommand("SELECT `shop_url`, `token` FROM `sears_shop_details` WHERE `merchant_id` ='".$merchant_id."' ")->queryOne();
        $shop=$selectShop['shop_url'];
        $token=$selectShop['token'];
        
        $sc = new ShopifyClientHelper($shop, $token,SEARS_APP_KEY,SEARS_APP_SECRET);
                
        $response=array();
        if ($planType=='1 Year Subscription Plan' || $planType=='6 Months Subscription Plan'){
            $response=$sc->call('GET','/admin/application_charges/'.$payment_id.'.json');
        }else{
            $response=$sc->call('GET','/admin/recurring_application_charges/'.$payment_id.'.json');
        }

        if($response && !isset($response['errors']))
        {
            $html=$this->render('viewpayment',array('data'=>$response),true);
        }
        return $html;     
    }*/
}
