<?php

namespace backend\controllers;

use Yii;
use common\models\JetMerchantsHelp;
use common\models\JetMerchantsHelpSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JetmerchantshelpController implements the CRUD actions for JetMerchantsHelp model.
 */
class JetmerchantshelpController extends MainController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * Lists all JetMerchantsHelp models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new JetMerchantsHelpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JetMerchantsHelp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new JetMerchantsHelp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JetMerchantsHelp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing JetMerchantsHelp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())  ) 
        {
         $message="";
        // $to = $model->merchant_email_id;
         $subject = $model->subject;
         
        $mer_email=$model->merchant_email_id;
	 	
        
        $headers_mer = "MIME-Version: 1.0" . chr(10);
        $headers_mer .= "Content-type:text/html;charset=iso-8859-1" . chr(10);
        $headers_mer .= 'From: support@cedcommerce.com' . chr(10);
        $headers_mer .= 'Bcc: amitkumar@cedcoss.com' . chr(10);
        $headers_mer .= 'Bcc: kshitijverma@cedcoss.com' . chr(10);
        $hostname = Yii::getAlias('@hostname').'/';
        
        $etx_mer .='
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
					                                                      '.$model->merchant_name.'
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
					
					         <table  st-sortable="banner"  border="0" cellpadding="0" cellspacing="0" width="600px" align="center">
					            <tbody>
					               <tr>
					                  <td>
					                     <table class="devicewidth" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
					                        <tbody>
					                           <tr>
					                              <td width="100%">
					                                 <table class="devicewidth" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
					                                    <tbody>
					                                       <tr>
					                                          <!-- start of image -->
					                                          <td st-image="banner-image" align="center">
					                                             <div class="imgpop">
					                                                
					                                             </div>
					                                             
					                                          </td>
					                                       </tr>
					                                    </tbody>
					                                 </table>
					                                 <!-- end of image -->
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
					                                          <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="20">&nbsp;</td>
					                                       </tr>
					                                       <!-- Spacing -->
					                                       <tr>
					                                          <td bgcolor="#ffffff">
					                                             <table class="devicewidthinner" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
					                                                <tbody>
					                                                   <!-- Title -->
					                                                   <tr>
					                                                      <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px; color: #018001; text-align:center; line-height: 24px; font-weight:bold;">
					                                                        Thank you for your interest in Shopify Jet-Integration app.
					                                                      </td>
					                                                   </tr>
					                                                   <!-- End of Title -->
					                                                   <!-- spacing -->
					                                                   <tr>
					                                                      <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="15" width="100%">&nbsp;</td>
					                                                   </tr>
					                                                   <!-- End of spacing -->
					                                                   <!-- content -->
					                                                      		
					                                                   <!--(Start) Submitted Query by client -->   		
					                                                   <tr>
					                                                      <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;">
					                                                         <b>your query given below ... </b>
					                                                      </td>
					                                                   </tr>
					                                                   <!-- End of content -->
					                                                   <!-- order details  -->
					                                                   <tr>
					                                                      <td align="center" style="padding-top:10px;padding-bottom:20px;">
					                                                         <table align="center" width="100%" border="1" style="border-color:#707070;">
					                                                            <tr>
					                                                               <td align="left" style="color: #707070;font-weight:bold;font-family: arial;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;">
					                                                                  <pre>'.$model->query.'</pre>
					                                                               </td>
					                                                      		   
					                                                      		</tr>
					                                                      	</table>
					                                                      </td>
					                                                   </tr>   		
					                                                   <!--(End) Submitted Query by client -->   		
					                                                  
					                                                   <!--(Start) Solution for Query -->   		
					                                                   <tr>
					                                                      <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;">
					                                                         <b>Solution for your query given below ... </b>
					                                                      </td>
					                                                   </tr>
					                                                   <!-- End of content -->
					                                                   <!-- order details  -->
					                                                   <tr>
					                                                      <td align="center" style="padding-top:10px;">
					                                                         <table align="center" width="100%" border="1" style="border-color:#707070;">
					                                                            <tr>
					                                                               <td align="left" style="color: #707070;font-weight:bold;font-family: arial;padding-left:3px;padding-right:3px;padding-top:7px;padding-bottom:7px;">
					                                                                  <pre>'.$model->solution.'</pre>
					                                                               </td>
					                                                      		</tr>
					                                                      	</table>
					                                                      </td>
					                                                   </tr>
																		<!--(End) Solution for Query -->					                                                                  		
					                                                   <!-- end of order details -->
					                                                   <!-- Spacing -->
					                                                   <tr>
					                                                      <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="15" width="100%">&nbsp;</td>
					                                                   </tr>
					                                                   
					                                                   
					                                                   <!-- spacing -->
					                                                   <tr>
					                                                      <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="15" width="100%">&nbsp;</td>
					                                                   </tr>
					                                                   <!-- End of spacing -->
					                                                    
					                                                   
					                                                   <!-- spacing -->
					                                                   <tr>
					                                                      <td style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;" height="15" width="100%">&nbsp;</td>
					                                                   </tr>
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
					                                             <b>Eamil : </b> <a href="http://support.cedcommerce.com/">support.cedcommerce.com</a>   |   <b>Web :</b> <a href="http://cedcommerce.com/">cedcommerce.com</a> 
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
					   </html>'.chr(10);
        
        
        
		mail($mer_email,$subject, $etx_mer, $headers_mer);
        
		$model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing JetMerchantsHelp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the JetMerchantsHelp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JetMerchantsHelp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JetMerchantsHelp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
