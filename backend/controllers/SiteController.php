<?php

namespace backend\controllers;

use backend\models\WalmartShopDetails;
use backend\components\Data;
use common\models\Admin;
use common\models\AdminLoginForm;
use common\models\JetExtensionDetail;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;


/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public $detailArray = array();
    public $P_avail = 0;
    public $P_complete = 0;
    public $p_under = 0;
    /* public function behaviors()
     {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                     [
                         'actions' => ['login', 'error'],
                         'allow' => true,
                     ],
                     [
                         'actions' => ['logout','header', 'index','view','sendmail','mailto','shopifymail','mailtoclient','readcsv','liveproducts','reviewproducts','completeorders','activemerchantstoday','activemerchantsyesterday','activemerchantssevendays','activemerchantsfifteendays','activemerchantsonemonth','communicationdetails','createcsv','exportcsv'],
                         'allow' => true,
                         'roles' => ['@'],
                     ],

                     [
                         'actions' => ['shopifyinfo','index'],
                         'allow' => true,
                     ],
                     [
                         'actions' => ['saveshopifyinfo'],
                         'allow' => true,
                     ],
                 ],
             ],
             'verbs' => [
                 'class' => VerbFilter::className(),
                 'actions' => [
                     'logout' => ['post'],
                 ],
             ],
         ];
     }*/

    /**
     * @inheritdoc
     */
    /* public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    } */


    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $connection = Yii::$app->getDb();

        date_default_timezone_set('Asia/Kolkata');
        $countArray = array();

        $today = count($connection->createCommand("SELECT  `merchant_id` from `jet_active_merchants` WHERE updated_at LIKE '%" . date('Y-m-d') . "%'")->queryAll());

        $yesterday = count($connection->createCommand("SELECT  `merchant_id` from `jet_active_merchants` WHERE updated_at LIKE '%" . date('Y-m-d', strtotime("-1 days")) . "%'")->queryAll());

        $sevenDays = count($connection->createCommand('select `merchant_id` from `jet_active_merchants` where  `updated_at` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)')->queryAll());

        $fifteenDays = count($connection->createCommand('select `merchant_id` from `jet_active_merchants` where  `updated_at` >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)')->queryAll());

        $month = count($connection->createCommand('select `merchant_id` from `jet_active_merchants` where  `updated_at` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)')->queryAll());

        $countArray['today'] = $today;
        $countArray['yesterday'] = $yesterday;
        $countArray['sevenday'] = $sevenDays;
        $countArray['fifteenday'] = $fifteenDays;
        $countArray['onemonth'] = $month;

        unset($today);
        unset($yesterday);
        unset($sevenDays);
        unset($fifteenDays);
        unset($month);
        $purchased = count($connection->createCommand("SELECT  `merchant_id` from `jet_extension_detail` WHERE status ='Purchased'")->queryAll());
        $license_expired = count($connection->createCommand("SELECT  `merchant_id` from `jet_extension_detail` WHERE status ='License Expired'")->queryAll());
        $not_purchase = count($connection->createCommand("SELECT  `merchant_id` from `jet_extension_detail` WHERE status ='Not Purchase'")->queryAll());
        $trial_expired = count($connection->createCommand("SELECT  `merchant_id` from `jet_extension_detail` WHERE status ='Trial Expired'")->queryAll());
        $install = count($connection->createCommand("SELECT  `merchant_id` from `jet_extension_detail` WHERE app_status ='install'")->queryAll());
        $uninstall = count($connection->createCommand("SELECT  `merchant_id` from `jet_extension_detail` WHERE app_status ='uninstall'")->queryAll());

        $extArray = array();

        $extArray['license_expired'] = $license_expired;
        $extArray['purchased'] = $purchased;
        $extArray['not_purchase'] = $not_purchase;
        $extArray['trial_expired'] = $trial_expired;
        $extArray['install'] = $install;
        $extArray['uninstall'] = $uninstall;

        unset($purchased);
        unset($license_expired);
        unset($not_purchase);
        unset($trial_expired);
        unset($install);
        unset($uninstall);
        return $this->render('index', [
            'active' => $countArray,
            'extArray' => $extArray,

        ]);
    }

    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = JetExtensionDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLiveproducts()
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $connection = Yii::$app->getDb();
        $array = array();

        $model = $connection->createCommand('select DISTINCT `merchant_id` from `jet_product` where  `status` = "Available for Purchase"')->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }
        $model = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);

        return $this->render('liveproducts', ['model' => $dataProvider]);
    }

    public function actionReviewproducts()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $connection = Yii::$app->getDb();

        $array = array();

        $model = $connection->createCommand('select DISTINCT `merchant_id` from `jet_product` where  `status` = "Under Jet Review"')->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }
        $model = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);
        return $this->render('reviewproducts', ['model' => $dataProvider]);
    }

////////////////////////////////////////////////////////////////////////////

    public function actionCompleteorders()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $connection = Yii::$app->getDb();
        $array = array();

        $model = $connection->createCommand('select DISTINCT `merchant_id` from `jet_order_detail` where  `status` = "complete"')->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }
        $model = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);
        return $this->render('completeorders', ['model' => $dataProvider]);
    }

//////////////////////////////////////////////////////////////////////////

    public function actionActivemerchantstoday()
    {
        date_default_timezone_set('Asia/Kolkata');
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $connection = Yii::$app->getDb();
        $array = array();
        $model = $connection->createCommand("SELECT  `merchant_id` from `jet_active_merchants` WHERE updated_at LIKE '%" . date('Y-m-d') . "%'")->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }
        $model_today = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model_today,
        ]);
        return $this->render('activemerchantstoday', ['model' => $dataProvider]);
    }

    //////////////////////////////////////////////////////////////////////////

    public function actionActivemerchantsyesterday()
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $connection = Yii::$app->getDb();
        date_default_timezone_set('Asia/Kolkata');
        $array = array();
        $model = $connection->createCommand("SELECT  `merchant_id` from `jet_active_merchants` WHERE updated_at LIKE '%" . date('Y-m-d', strtotime("-1 days")) . "%'")->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }
        $model = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);

        return $this->render('activemerchantsyesterday', ['model' => $dataProvider]);
    }

    //////////////////////////////////////////////////////////////////////////

    public function actionActivemerchantssevendays()
    {
        date_default_timezone_set('Asia/Kolkata');
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $connection = Yii::$app->getDb();
        $array = array();

        $model = $connection->createCommand('select `merchant_id` from `jet_active_merchants` where  `updated_at` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)')->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }

        $model = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);
        return $this->render('activemerchantssevendays', ['model' => $dataProvider]);
    }

    //////////////////////////////////////////////////////////////////////////

    public function actionActivemerchantsfifteendays()
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $connection = Yii::$app->getDb();
        date_default_timezone_set('Asia/Kolkata');
        $array = array();

        $model = $connection->createCommand('select `merchant_id` from `jet_active_merchants` where  `updated_at` >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)')->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }
        $model = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);

        return $this->render('activemerchantsfifteendays', ['model' => $dataProvider]);
    }


    //////////////////////////////////////////////////////////////////////////

    public function actionActivemerchantsonemonth()
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $connection = Yii::$app->getDb();
        date_default_timezone_set('Asia/Kolkata');
        $array = array();

        $model = $connection->createCommand('select `merchant_id` from `jet_active_merchants` where  `updated_at` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)')->queryAll();
        foreach ($model as $key1 => $val1) {
            $m_id = $val1['merchant_id'];
            $array[] = $m_id;
        }
        $model = JetExtensionDetail::find()->where(['in', 'merchant_id', $array]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);
        return $this->render('activemerchantsonemonth', ['model' => $dataProvider]);
    }

    public function actionLogin()
    {
        $session = Yii::$app->session;
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new AdminLoginForm();
        $data = Admin::find()->all();

        if ($model->load(Yii::$app->request->post()) && empty($data)) {  //singup
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect(['index']);
                }
            }

        } elseif ($model->load(Yii::$app->request->post()) && $model->login()) {

            $url = method_exists(Yii::$app->user, 'getRedirectUrl') ? Yii::$app->user->getRedirectUrl() : false;
            if($url)
                return $this->redirect($url);
            else
                return $this->redirect(['index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSendmail()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        return $this->render('sendmail');
    }

    public function actionMailto()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $this->layout = "main2";

        $mer_email = Yii::$app->getRequest()->getQueryParam('clientMailId');
        $subject = Yii::$app->getRequest()->getQueryParam('subject');
        $message = Yii::$app->getRequest()->getQueryParam('message');
        $hostname = Yii::getAlias('@hostname') . '/';
        $headers_mer = "MIME-Version: 1.0" . chr(10);
        $headers_mer .= "Content-type:text/html;charset=iso-8859-1" . chr(10);
        $headers_mer .= 'From: shopify@cedcommerce.com' . chr(10);
        $headers_mer .= 'Bcc: james@cedcommerce.com' . chr(10);
        //$headers_mer .= 'Bcc: kshitijverma@cedcoss.com' . chr(10);
        $headers_mer .= 'Bcc: prateekshrivastava@cedcoss.com' . chr(10);


        $etx_mer .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml">
						   <head>
						      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
						      <title>Order acknowledgedment Mail</title>
				
						      <style type="text/css">
						         /* Client-specific Styles */
						         div, p, a, li, td { -webkit-text-size-adjust:none; }
						         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
						         html{width: 100%; }
						         body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
						         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
						         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
						         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing. */
						         #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
						         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
						         a img {border:none;}
						         .image_fix {display:block;}
						         p {margin: 0px 0px !important;}
						         table td {border-collapse: collapse;}
						         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
						         a {color: #33b9ff;text-decoration: none;text-decoration:none!important;}
						         /*STYLES*/
						         table[class=full] { width: 100%; clear: both; }
						         /*IPAD STYLES*/
						         @media only screen and (max-width: 640px) {
						         a[href^="tel"], a[href^="sms"] {
						         text-decoration: none;
						         color: #33b9ff; /* or whatever your want */
						         pointer-events: none;
						         cursor: default;
						         }
						         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
						         text-decoration: default;
						         color: #33b9ff !important;
						         pointer-events: auto;
						         cursor: default;
						         }
						         table[class=devicewidth] {width: 440px!important;text-align:center!important;}
						         table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
						         img[class=banner] {width: 440px!important;height:220px!important;}
						         img[class=col2img] {width: 440px!important;height:220px!important;}
    
    
						         }
						         /*IPHONE STYLES*/
						         @media only screen and (max-width: 480px) {
						         a[href^="tel"], a[href^="sms"] {
						         text-decoration: none;
						         color: #33b9ff; /* or whatever your want */
						         pointer-events: none;
						         cursor: default;
						         }
						         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
						         text-decoration: default;
						         color: #33b9ff !important;
						         pointer-events: auto;
						         cursor: default;
						         }
						         table[class=devicewidth] {width: 280px!important;text-align:center!important;}
						         table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
						         img[class=banner] {width: 280px!important;height:140px!important;}
						         img[class=col2img] {width: 280px!important;height:140px!important;}
    
    
						         }
    
						      </style>
						   </head>
						   <body>
    
						<!-- Start of preheader -->
						<table id="backgroundTable" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bg-color="#f2f2f2" style="background-color:#f2f2f2;">
						   <tr>
						      <td>
						         <table width="600px" align="center" bgcolor="#fcfcfc" border="0" cellpadding="0" cellspacing="0">
						            <tr>
						               <td>
						                  <table  st-sortable="preheader" bgcolor="#fcfcfc" border="0" cellpadding="0" cellspacing="0" width="600px" align="center">
						                     <tbody>
						                        <tr>
						                           <td>
						                              <table class="devicewidth" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
						                                 <tbody>
						                                    <tr>
						                                       <td width="100%">
						                                          <table class="devicewidth" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
						                                             <tbody>
						                                                <!-- Spacing -->
						                                                <tr>
						                                                   <td height="20" width="100%" bgcolor="#ffffff"></td>
						                                                </tr>
						                                                <!-- Spacing -->
						                                                <tr>
						                                                   <td style="font-family: Helvetica, arial, sans-serif; font-size: 17px;color: #282828; font-weight:bold;" st-content="preheader" align="left" valign="middle" width="50%" bgcolor="#ffffff">
						                                                      ' . $mer_email . '
						                                                   </td>
						                                                   <td style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #282828" st-content="preheader" align="right" valign="middle" width="50%" bgcolor="#ffffff">
						                                                      <a href="http://cedcommerce.com/" target="_blank"><img src="<?= $hostname ?>jet/images/logo-mail.jpg" width="165px"></a>
						                                                   </td>
						                                                </tr>
						                     
						                                                <!-- Spacing -->
						                                                <tr>
						                                                   <td height="20" width="100%" bgcolor="#ffffff"></td>
						                                                </tr>
						                                                <!-- Spacing -->
						                                             </tbody>
						                                          </table>
						                                       </td>
						                                    </tr>
						                                 </tbody>
						                              </table>
						                           </td>
						                        </tr>
						                     </tbody>
						                  </table>
						         <table  st-sortable="full-text" bgcolor="#fcfcfc" border="0" cellpadding="0" cellspacing="0" width="600px" align="center">
						            <tbody>
						               <tr>
						                  <td style="padding-left:15px;padding-right:15px;">
						                     <table class="devicewidth" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
						                        <tbody>
						                           <tr>
						                              <td width="100%">
						                                 <table class="devicewidth" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="600">
						                                    <tbody>
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="20"> <img src="<?= $hostname ?>jet/images/line.png" width="100%" ></td>
						                                       </tr>
						                 
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td bgcolor="#ffffff">
						                                             <table class="devicewidthinner" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
						                                                <tbody>
						                                                   <!-- Title -->
						                                                   <tr>
																				<td width="100%"  height="70px" align="center" bgcolor="#5f5e5d" style="font-family: aerial,Helventica,sans-serif;font-weight: bold;color:#fff; font-size:20px;">
																					<pre>' . $subject . '</pre>
																				</td>
																			</tr>
						                                                   <!-- End of Title -->
						                                                   <!-- spacing -->
						                       
						                                                   <!-- End of spacing -->
						                                                   <!-- order details  -->
						                                                   <tr>
						                                                      <td align="center" style="padding-top:10px;">
						                                                         <table align="center" width="100%" border="0" style="border-color:#707070;">
						                                                            <tr>
						                                                               <td align="left" style="font-size: 18px; font-family: verdana;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;">
						                                                                  ' . $message . '
						                                                               </td>
						                                                      		</tr>
						                                                      	</table>
						                                                      </td>
						                                                   </tr>
						                                                   <tr>
									                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="20"> <img src="<?= $hostname ?>jet/images/line.png" width="100%" ></td>
									                                       </tr>
																			<!--(End) Solution for Query -->
						                                                   <!-- end of order details -->
						                       
						                                                   <tr>
						                                                      <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px; color: #976F9E; text-align:center; line-height: 24px; font-weight:bold;">
						                                                         For any Query / Help / Suggestion, Please contact us via
						                                                      </td>
						                                                   </tr>
						                                                   <tr>
						                                                      <td align="center" style="padding-top:15px;padding-bottom:15px;">
						                                                         <table align="center" width="100%" border="1" style="border-color:#707070;">
						                                                            <tr>
						                                                               <td align="center" style="color: #707070;font-family: arial;font-weight: bold;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;">
						                                                                  <img src="<?= $hostname ?>jet/images/ZopimChat.png" width="50px">
						                                                               </td>
						                                                               <td align="center" style="color: #707070;font-family: arial;font-weight: bold;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;">
						                                                                  <img src="<?= $hostname ?>jet/images/Ticket.png" width="50px">
						                                                               </td>
						                                                               <td align="center" style="color: #707070;font-family: arial;font-weight: bold;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;">
						                                                                   <img src="<?= $hostname ?>jet/images/Skype.png" width="50px">
						                                                            </tr>
						                                                            <tr>
						                                                               <td style="color: #7d7d7d;font-family: arial;font-size: 14px;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;text-align: center; line-height:25px;width:33%;">
						                                                                  Zopm Chat
						                                                               </td>
						                                                               <td style="color: #7d7d7d;font-family: arial;font-size: 14px;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;text-align: center;line-height:25px;width:33%;">
						                                                                  Ticket</br> (<a href="http://support.cedcommerce.com/" style="color:#976F9E; text-decoration:none; font-size:15px; font-family:arial;">support.cedcommerce.com</a>)
						                                                               </td>
						                                                               <td style="color: #7d7d7d;font-family: arial;font-size: 14px;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;text-align: center;line-height:25px;width:33%;">
						                                                                  Skype</br> (Skype id : cedcommerce)
						                                                               </td>
						                                                            </tr>
						                                                         </table>
						                                                      </td>
						                                                   </tr>
						                                                   <!-- Spacing -->
						                                                </tbody>
						                                             </table>
						                                          </td>
						                                       </tr>
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="20">&nbsp;</td>
						                                       </tr>
						                                       <!-- Spacing -->
						                                    </tbody>
						                                 </table>
						                              </td>
						                           </tr>
						                        </tbody>
						                     </table>
						                  </td>
						               </tr>
						            </tbody>
						         </table>
						         <!-- End of Full Text -->
    
						         <!-- Start of Right Image -->
						         <table id="" st-sortable="right-image" bgcolor="#fcfcfc" border="0" cellpadding="0" cellspacing="0" width="600px" align="center">
						            <tbody>
						               <tr>
						                  <td>
						                     <table class="devicewidth" align="center" border="0" cellpadding="0" cellspacing="0" width="600" >
						                        <tbody>
						                           <tr>
						                              <td width="100%">
    
						                              </td>
						                           </tr>
						                        </tbody>
						                     </table>
						                  </td>
						               </tr>
						            </tbody>
						         </table>
						         <!-- End of Right Image -->
    
						         <!-- Start of footer -->
						         <table  st-sortable="footer" bgcolor="" border="0" cellpadding="0" cellspacing="0" width="600px" align="center">
						            <tbody>
						               <tr>
						                  <td>
						                     <table class="devicewidth" align="center" bgcolor="" border="0" cellpadding="0" cellspacing="0" width="600">
						                        <tbody>
						                           <tr>
						                              <td width="100%">
						                                 <table class="devicewidth" align="center" bgcolor="" border="0" cellpadding="0" cellspacing="0" width="600">
						                                    <tbody>
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="10">&nbsp;</td>
						                                       </tr>
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;">
						                                             Thanks and Best Reagards
						                                           </td>
						                                       </tr>
						                                       <tr>
						                                          <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;">
						                                             Cedcommerce Support Team
						                                           </td>
						                                       </tr>
						                                       <tr>
						                                          <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;">
						                                             <b>Email : </b> <a href="http://support.cedcommerce.com/">support.cedcommerce.com</a>   |   <b>Web :</b> <a href="http://cedcommerce.com/">cedcommerce.com</a>
						                                           </td>
						                                       </tr>
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="20">&nbsp;</td>
						                                       </tr>
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td>
						                                             <!-- Social icons -->
						                                             <table class="devicewidth" align="center" border="0" cellpadding="0" cellspacing="0" width="150">
						                                                <tbody>
						                                                   <tr>
						                                                      <td align="center" height="43" width="43">
						                                                         <div class="imgpop">
						                                                            <a href="https://www.facebook.com/CedCommerce/"><img alt="" src="<?= $hostname ?>jet/images/Polygon-fb.png"></a>
						                                                         </div>
						                                                      </td>
						                                                      <td style="font-size:1px; line-height:1px;" align="left" width="20">&nbsp;</td>
						                                                      <td align="center" height="43" width="43">
						                                                         <div class="imgpop">
						                                                            <a href="https://plus.google.com/u/0/118378364994508690262"><img alt="" src="<?= $hostname ?>jet/images/Polygon-google.png"></a>
						                                                         </div>
						                                                      </td>
						                                                      <td style="font-size:1px; line-height:1px;" align="left" width="20">&nbsp;</td>
						                                                      <td align="center" height="43" width="43">
						                                                         <div class="imgpop">
						                                                            <a href="https://www.linkedin.com/company/cedcommerce"><img alt="" src="<?= $hostname ?>jet/images/Polygon-linkedin.png"></a>
						                                                         </div>
						                                                      </td>
						                                                      <td align="center" height="43" width="43">
						                                                         <div class="imgpop">
						                                                            <a href="https://twitter.com/cedcommerce"><img alt="" src="<?= $hostname ?>jet/images/polygon-tweet_1.png"></a>
						                                                         </div>
						                                                      </td>
						                                                   </tr>
						                                                </tbody>
						                                             </table>
						                                             <!-- end of Social icons -->
						                                          </td>
						                                       </tr>
						                                       <!-- Spacing -->
						                                       <tr>
						                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="25">&nbsp;</td>
						                                       </tr>
						                                       <!-- Spacing -->
						                                    </tbody>
						                                 </table>
						                              </td>
						                           </tr>
						                        </tbody>
						                     </table>
						                  </td>
						               </tr>
						            </tbody>
						         </table>
						         <!-- End of footer -->
						               </td>
						            </tr>
						         </table>
						      </td>
						   </tr>
						</table>
    
						 </body>
						   </html>' . chr(10);
        mail($mer_email, $subject, $etx_mer, $headers_mer);
        /*
         $connection = Yii::$app->getDb();
         $connection->createCommand('INSERT INTO `mailto_client` (`email_id`,`subject`,`message`) values("'.$mer_email.'","'.$subject.'","'.$message.'")')->execute();
         */
    }

    public function actionMailtoclient()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        
        $connection = Yii::$app->getDb();
        $client = $connection->createCommand('select email from newUser ')->queryAll();

        foreach ($client as $key) {
            echo "<pre>";
            print_r($key['email']);
            $this->shopifymail($key['email']);
        }

//     	echo "<pre>";
//     	print_r($client);
//     	die;
    }

    public function shopifymail($emailid)
    {
        $this->layout = "main2";

// 			$mer_email=Yii::$app->getRequest()->getQueryParam('clientMailId');
//     		$subject=Yii::$app->getRequest()->getQueryParam('subject');
//     		$message=Yii::$app->getRequest()->getQueryParam('message');

        $headers_mer = "MIME-Version: 1.0" . chr(10);
        $headers_mer .= "Content-type:text/html;charset=iso-8859-1" . chr(10);
        $headers_mer .= 'From: shopify@cedcommerce.com' . chr(10);
        $headers_mer .= 'Bcc: james@cedcommerce.com' . chr(10);
//  	    	$headers_mer .= 'Bcc: kshitijverma@cedcoss.com' . chr(10);
//  	    	$headers_mer .= 'Bcc: abhishekjaiswal@cedcoss.com' . chr(10);
        //$mer_email='akshuklait@gmail.com';
        $mer_email = $emailid;
        $subject = 'Sell your Shopify Products on Jet';

        $etx_mer .= '
	    				<html>
								<head>
									<style type="text/css">
										td#banner img {
										    width: 100%;
										}
									</style>
								</head>
								<body style="margin:0px;padding:0px; ">
										<table width="600px"  cellpadding="0" cellspacing="0" align="center" style="background-color: #F4F4F4;padding: 1%">
												<tbody>
														<!--header-->
													<tr style="">
														<td style="">
															<table style="width:100%">
																<tr style="">
																	<td style="">
																		<a href="https://apps.shopify.com/jet-integration" target="_blank"><img src="<?= $hostname ?>jet/images/logo1.png" style="width: 125px"/></a>										
																	</td>
																	<td style="text-align:right; ">		
																		<a href="https://www.shopify.in/" target="_blank"><img src="<?= $hostname ?>jet/images/shopify-logo.png" style="width: 125px"/>	</a>																								
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<img src="<?= $hostname ?>jet/images/line.png" style="width: 100%">
														</td>
													</tr>
													<tr>
														
														<td>
															<h2 style="background-color: #e2e1e0;padding:1%;color: #727a8c;">Want to sell your products on jet.com?</h2>
														</td>
													</tr>
													<tr>
														<td id="banner">
															<a href="https://apps.shopify.com/jet-integration" target="_blank"><img src="<?= $hostname ?>jet/images/jet-integration-banner.png" style="widh:100%; "></a>							
														</td>
													</tr>
													<tr>
														<td style="padding-top:10px;">
															<img src="<?= $hostname ?>jet/images/line.png" style="width: 100%">
														</td>
													</tr>
														<!--header end-->	
															<!--main section start-->
														<tr>
															<td>
																<table width="100%" style=" font-weight: bold; font-family: arial,Helvetica,sans-serif;">
																	<tr>
																		<td align="center" style="font-size:24px;padding-bottom:15px;"><a style="color:#f45c05; text-decoration: none;" href="https://apps.shopify.com/jet-integration" target="_blank"> Jet-Integration App </a></td>
																	</tr>									
																</table>
															</td>
														</tr>
														<tr>
															<td>
	    														<p style="text-align: justify;color: #727a8c">
																	 Want to Grow Your Business to next level? Check out Jet-Integration app to expand your business.              
																</p>
																<p style="text-align: justify;color: #727a8c"">
																	 Jet-Integration provides an ideal solution to upload products,import and manage orders from Jet Marketplace with pretty much automation.             
																</p>
															</td>
														</tr>
														<tr>
															<td>
																<br>
																<center><a target="_blank" href="https://apps.shopify.com/jet-integration" style="text-decoration: none;"><span style="background-color: #78B657; border-radius: 3px; color: white;  padding: 10px 30px; ">View App</span></a></center>
															</td>
														</tr>
										    			<tr>
															<td>																
																&nbsp;
															</td>
														</tr>
														
													<tr ><!-- footer -->
													<!-- Start of footer -->
													         <table style="background-color:#E1E3E4 ;" st-sortable="footer"  width="600px" border="0" cellpadding="0" cellspacing="0"align="center">
													            <tbody>
													               <tr>
													                  <td>
													                     <table class="devicewidth" align="center" bgcolor="" border="0" cellpadding="0" cellspacing="0" width="600">
													                        <tbody>
													                           <tr>
													                              <td width="100%">
													                                 <table class="devicewidth" align="center" bgcolor="" border="0" cellpadding="0" cellspacing="0" width="600">
													                                    <tbody>
													                                       <!-- Spacing -->
													                                       <tr>
													                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="10">&nbsp;</td>
													                                       </tr>
													                                       <!-- Spacing -->
													                                       <tr>
													                                          <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #78b657; text-align:center; line-height: 24px;">
													                                            	<b> <a style="text-decoration: none;color: #888888;" href="https://apps.shopify.com/jet-integration">Jet-Integration App</a>  |  <a style="text-decoration: none;color: #888888;" href="http://support.cedcommerce.com/">support.cedcommerce.com</a>   |   <a  style="text-decoration: none;color: #888888;" href="http://cedcommerce.com/">cedcommerce.com</a> </b>
													                                           </td>
													                                       </tr>
													                                       <tr>
													                                          <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;">
													                                             Best Reagards | Cedcommerce Shopify Team
													                                           </td>
													                                       </tr>
													                                       
													                                    </tbody>
													                                 </table>
													                              </td>
													                           </tr>
													                        </tbody>
													                     </table>
													                  </td>
													               </tr>
													            </tbody>
													         </table>
													         <!-- End of footer -->
								   </tr><!-- footer end -->
								  </tbody>
								</table>
								</body>
								</html>		
	    					
						' . chr(10);
        mail($mer_email, $subject, $etx_mer, $headers_mer);
        /*
         $connection = Yii::$app->getDb();
         $connection->createCommand('INSERT INTO `mailto_client` (`email_id`,`subject`,`message`) values("'.$mer_email.'","'.$subject.'","'.$message.'")')->execute();
         */
        return;
    }

    public function actionCreatecsv()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $connection = Yii::$app->getDb();

        $client = $connection->createCommand("select `merchant_id` ,`email`,`shopurl`,`status`,`app_status`,`expire_date`   from  `jet_extension_detail`  ")->queryAll();
// 		echo "<pre>";
// 		print_r($client);
// 		die;

        if (!file_exists(\Yii::getAlias('@webroot') . '/var/email_csv-' . date('Y-m-d'))) {
            mkdir(\Yii::getAlias('@webroot') . '/var/email_csv-' . date('Y-m-d'), 0775, true);
        }
        $base_path = \Yii::getAlias('@webroot') . '/var/email_csv-' . date('Y-m-d') . '/' . time() . '.csv';
        $file = fopen($base_path, "w");
        $headers = array('Merchant id', 'Shop Url', 'Email id', 'Purchase Status', 'Install Status', 'Expire Date');
        $row = array();
        foreach ($headers as $header) {
            $row[] = $header;
        }
        fputcsv($file, $row);

        $csvdata = array();
        $i = 0;
        foreach ($client as $value) {
// 			echo "<pre>";
// 			print_r($value);
// 			die;
            $csvdata[$i]['Merchant id'] = $value['merchant_id'];
            $csvdata[$i]['Shop Url'] = $value['shopurl'];
            $csvdata[$i]['Email id'] = $value['email'];
            $csvdata[$i]['Purchase Status'] = $value['status'];
            $csvdata[$i]['Install Status'] = $value['app_status'];
            $csvdata[$i]['Expire Date'] = $value['expire_date'];
            $i++;
        }

// 					echo "<pre>";
// 					print_r($csvdata);
// 					die;
        foreach ($csvdata as $v) {
            $row = array();
            $row[] = $v['Merchant id'];
            $row[] = $v['Shop Url'];
            $row[] = $v['Email id'];
            $row[] = $v['Purchase Status'];
            $row[] = $v['Install Status'];
            $row[] = $v['Expire Date'];

            fputcsv($file, $row);
        }
        fclose($file);
        //$link=Yii::$app->request->baseUrl.'/var/product_csv-'.$merchant_id.'/products.csv';
        $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content = $encode . file_get_contents($base_path);
        return \Yii::$app->response->sendFile($base_path);
    }

    // @todo:: Export bonanza clients details
    public function actionExportbonuser()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $sql = "SELECT `bsd`.`merchant_id`,`shop_url`,`merchant`.`email`,`purchase_status`,`install_status`,`expired_on` FROM `bonanza_shop_details` AS `bsd` INNER JOIN `merchant` ON `bsd`.`merchant_id`=`merchant`.`merchant_id` ";
        $client = Data::integrationSqlRecords($sql, 'all', 'select');
        $filePath = \Yii::getAlias('@rootdir') . '/var/bonanza/email_csv';
        if (!file_exists($filePath)) {
            mkdir($filePath, 0775, true);
        }
        $base_path = $filePath . '/' . date('d-m-Y') . '.csv';
        $file = fopen($base_path, "a+");
        $headers = ['Merchant id', 'Shop Url', 'Email id', 'Purchase Status', 'Install Status', 'Expire Date'];
        $row = $csvdata = [];
        foreach ($headers as $header) {
            $row[] = $header;
        }
        fputcsv($file, $row);

        $i = 0;
        foreach ($client as $value) {
            $csvdata[$i]['Merchant id'] = $value['merchant_id'];
            $csvdata[$i]['Shop Url'] = $value['shop_url'];
            $csvdata[$i]['Email id'] = $value['email'];
            $csvdata[$i]['Purchase Status'] = $value['purchase_status'];
            $csvdata[$i]['Install Status'] = $value['install_status'];
            $csvdata[$i]['Expire Date'] = $value['expired_on'];
            $i++;
        }

        foreach ($csvdata as $v) {
            $row = array();
            $row[] = $v['Merchant id'];
            $row[] = $v['Shop Url'];
            $row[] = $v['Email id'];
            $row[] = $v['Purchase Status'];
            $row[] = $v['Install Status'];
            $row[] = $v['Expire Date'];

            fputcsv($file, $row);
        }
        fclose($file);

        $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content = $encode . file_get_contents($base_path);
        return \Yii::$app->response->sendFile($base_path);
        //return Yii::$app->response->sendFile($base_path);
        return yii\web\Response::sendFile($base_path);
    }

    public function actionExportcsv()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        
        if (!file_exists(\Yii::getAlias('@webroot') . '/var/email_csv-' . date('Y-m-d'))) {
            mkdir(\Yii::getAlias('@webroot') . '/var/email_csv-' . date('Y-m-d'), 0775, true);
        }
        $base_path = \Yii::getAlias('@webroot') . '/var/email_csv-' . date('Y-m-d') . '/' . time() . '.csv';
        $file = fopen($base_path, "a+");
        $headers = array('Merchant id', 'Shop Url', 'Email id', 'Purchase Status', 'Install Status', 'Expire Date');
        $row = array();
        $value = array();

        foreach ($headers as $header) {
            $row[] = $header;
        }
        fputcsv($file, $row);

        $csvdata = array();
        $i = 0;
        $client = WalmartShopDetails::find()->joinWith(['walmartExtensionDetail'])->all();
        foreach ($client as $value) {
            if ($value['status'] == 1) {
                $appstatus = "install";
            } else {
                $appstatus = "uninstall";
            }

            $csvdata[$i]['Merchant id'] = $value['merchant_id'];
            $csvdata[$i]['Shop Url'] = $value['shop_url'];
            $csvdata[$i]['Email id'] = $value['email'];
            $csvdata[$i]['Purchase Status'] = $value['walmartExtensionDetail']['status'];
            $csvdata[$i]['Install Status'] = $appstatus;
            $csvdata[$i]['Expire Date'] = $value['walmartExtensionDetail']['expire_date'];
            $i++;
        }

        foreach ($csvdata as $v) {

            $row = array();
            $row[] = $v['Merchant id'];
            $row[] = $v['Shop Url'];
            $row[] = $v['Email id'];
            $row[] = $v['Purchase Status'];
            $row[] = $v['Install Status'];
            $row[] = $v['Expire Date'];

            fputcsv($file, $row);
        }
        fclose($file);
        //$link=Yii::$app->request->baseUrl.'/var/product_csv-'.$merchant_id.'/products.csv';
        $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content = $encode . file_get_contents($base_path);
        return Yii::$app->response->sendFile($base_path);


    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }

}
