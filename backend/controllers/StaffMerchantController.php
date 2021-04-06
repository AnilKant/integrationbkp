<?php

namespace backend\controllers;

use Yii;
use app\models\StaffAccountMembers;
use app\models\StaffAccountMembersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Xmlapi;
use app\models\JetExtensionDetail;
/**
 * StaffMerchantController implements the CRUD actions for StaffAccountMembers model.
 */
class StaffMerchantController extends MainController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * Lists all StaffAccountMembers models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }
        $searchModel = new StaffAccountMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaffAccountMembers model.
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
     * Creates a new StaffAccountMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaffAccountMembers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StaffAccountMembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StaffAccountMembers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $ip = JetExtensionDetail::IP;           // should be server IP address or 127.0.0.1 if local server
        $account = JetExtensionDetail::USER;        // cpanel user account name
        $passwd =JetExtensionDetail::PASSWORD;        // cpanel user password
        $port = JetExtensionDetail::PORT;                 // cpanel secure authentication port unsecure port# 2082
        $email_domain = JetExtensionDetail::DOMAIN; // email domain (usually same as cPanel domain)
        $email_quota = JetExtensionDetail::EMAIL_QUOTA; // default amount of space in megabytes  

        // check if overrides passed
        $email_user = "jet-merchant-".$model->merchant_id;
        $email_pass = "cedcoss007";

        //create user account
        $xmlapi = new Xmlapi($ip);
        $xmlapi->set_port($port);
        $xmlapi->password_auth($account, $passwd);
        $call = array('domain'=>$email_domain, 'email'=>$email_user, 'password'=>$email_pass, 'quota'=>$email_quota);
        $xmlapi->set_debug(0);      //output to error file  set to 1 to see error_log.
        $result = $xmlapi->api2_query($account, "Email", "delpop", $call); 
        if(isset($result->data->result) && $result->data->result==1)
        {
            $this->findModel($id)->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the StaffAccountMembers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaffAccountMembers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaffAccountMembers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
