<?php

namespace backend\controllers;

use backend\components\Data;
use Yii;
use yii\filters\VerbFilter;

;

class ExportMerchantDetailsController extends MainController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className () ,
                'actions' => [
                    'delete' => [ 'POST' ] ,
                ] ,
            ] ,
        ];
    }


    public function actionIndex()
    {
        if ( Yii::$app->user->isGuest ) {
            return Yii::$app->getResponse ()->redirect ( Yii::$app->getUser ()->loginUrl );
        }
        return $this->render ( 'index' );
    }

    public function actionGetApps()
    {
        if ( Yii::$app->user->isGuest ) {
            return Yii::$app->getResponse ()->redirect ( Yii::$app->getUser ()->loginUrl );
        }

        $postData = Yii::$app->request->post ();
        if ( isset( $postData[ 'app_type' ] ) && !empty( $postData[ 'app_type' ] ) ) {
            if ( $postData[ 'app_type' ] == 'new' ) {
                $Apps = [
                    'pricefalls' ,
                    'bestbuyca' ,
                    'etsy' ,
                    'bonanza' ,
                    'tophatter' ,
                    'wish' ,
                    'walmartca' ,
                    'fruugo' ,
                    'groupon' ,
                    'rakutenus'
                ];
            } else {
                $Apps = [ 'jet' , 'walmart' , 'newegg' , 'sears' , 'neweggca' ];
            }
            $html = '';
            foreach ($Apps as $value) {
                $html .= '<option value="' . $value . '">' . $value . '</option>';
            }

            return $html;
        }
    }

    public function actionExportCsv()
    {
        if ( Yii::$app->user->isGuest ) {
            return Yii::$app->getResponse ()->redirect ( Yii::$app->getUser ()->loginUrl );
        }
        $postData = Yii::$app->request->post ( 'Export' );
        if ( isset( $postData[ 'app_name' ] ) ) {
            $apps = implode ( ',' , $postData[ 'app_name' ] );
            $query = $email_query = '';

            foreach ($postData[ 'email_prefrence' ] as $value) {
                $query .= " `ms`.`subscription_data` LIKE '%" . $value . "%' OR";
            }
            $query = rtrim ( $query , 'OR' );
            if ( $postData[ 'app_type' ] == 'new' ) {
                $data_query = "SELECT DISTINCT `m`.`email` FROM `merchant` `m` LEFT JOIN `merchant_subscription` `ms` ON `m`.`merchant_id` =`ms`.`merchant_id` LEFT JOIN `merchant_db` `md` ON `ms`.`merchant_id`=`md`.`merchant_id` WHERE '" . $apps . "' LIKE CONCAT('%',`md`.`app_name`,'%') AND (" . $query . ")";
                $merchantIds = Data::integrationSqlRecords ( $data_query , "column" );
            } else {
                $data_query = "SELECT `ms`.`merchant_id` FROM `merchant_subscription` `ms` LEFT JOIN `merchant_db` `md` ON `ms`.`merchant_id`=`md`.`merchant_id` WHERE '" . $apps . "' LIKE CONCAT('%',`md`.`app_name`,'%') AND (" . $query . ")";
                $merchantId = Data::SqlRecords ( $data_query , "column" );
                if(!empty($merchantIds)){
                    foreach ($postData[ 'app_name' ] as $value) {
                        if($value == 'newegg')
                            $table = '`newegg_shop_detail`';
                        elseif ($value == 'neweggca')
                            $table = '`newegg_can_shop_detail`';
                        else
                            $table = '`' . $value . '_shop_details`';
                        $email_query .= 'SELECT DISTINCT `email` FROM '.$table.' WHERE `' . $value . '_shop_details`.`merchant_id` IN ('.implode(",",$merchantId).') UNION ';
                    }
                    $email_query = rtrim ( $email_query , 'UNION ' );
                    $merchantIds = Data::sqlRecords ($email_query,"column");
                }
            }

            $logPath = Yii::getAlias ( '@webroot' ) . '/var/merchant_csv';
            if ( !file_exists ( $logPath ) ) {
                mkdir ( $logPath , 0775 , true );
            }
            $base_path = $logPath . '/csv_export.csv';
            $file = fopen ( $base_path , "w" );

            $headers = array( 'EMAIL' );
            foreach ($headers as $header) {
                $row[] = $header;
            }
            fputcsv ( $file , $row );
            if(!empty($merchantIds)){
                foreach ($merchantIds as $value) {
                    $row = [];
                    $row[] = isset($value[ 'email' ])?$value[ 'email' ]:$value;
                    fputcsv ( $file , $row );
                }
            }
            fclose ( $file );
            $encode = "\xEF\xBB\xBF"; // UTF-8 BOM
            $content = $encode . file_get_contents ( $base_path );
            return Yii::$app->response->sendFile ( $base_path );
        }
    }
}