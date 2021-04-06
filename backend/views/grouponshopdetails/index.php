<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Merchant;
use backend\models\GrouponShopDetails;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GrouponShopDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Groupon Shop Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wish-shop-details-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /*echo Html::a('Create Wish Shop Details', ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function ($model){
            if ($model->install_status==0){
                return ['class'=>'error'];
            }elseif (($model->purchase_status==2)||($model->purchase_status==4)){
                return ['class'=>'danger'];
            }elseif ($model->purchase_status==3){
                return ['class'=>'success'];
            }elseif ($model->purchase_status==1){
                return ['class'=>'review'];
            }

        },
        'columns' => [
            'merchant_id',
            //'token',
            'shopurl',
            'owner_name',
            'email',
            [
                'label'=>'Config Set',
                'attribute' => 'config',
                'value' => function($data){
                    if ($data['config']) {
                        return "Yes";
                    } else {
                        return "No";
                    }
                },
                'filter' => ['no'=>'No', 'yes'=>'Yes']
            ],
            [
                'label'=>'Install Status',
                'attribute' => 'install_status',
                'format' => 'html',
                'value' => function($data){
                    $installStatus = '';
                    if ($data['install_status']) {
                        $installStatus = "Installed" . "<br>(" . $data['install_date'] . ")";
                    } else {
                        $installStatus = "UnInstalled" . "<br>(" . $data['uninstall_date'] . ")";
                    }
                    return $installStatus;
                },
                'filter' => ['1'=>'Installed', '0'=>'UnInstalled']
            ],
            [
                'label'=>'Install Date',
                'attribute' => 'install_date',
                'format' => 'date'
            ],
            [
                'label'=>'Uninstall Date',
                'attribute' => 'uninstall_date',
                'format' => 'date'
            ],
            [
                'label'=>'Purchase Status',
                'attribute' => 'purchase_status',
                'format' => 'html',
                'value' => function($data){
                    $purchaseStatus = '';
                    if ($data['purchase_status'] == GrouponShopDetails::PURCHASE_STATUS_TRAIL) {
                        $purchaseStatus = "Trial";
                    } elseif ($data['purchase_status'] == GrouponShopDetails::PURCHASE_STATUS_TRAILEXPIRE) {
                        $purchaseStatus = "Trial Expired";
                    } elseif ($data['purchase_status'] == GrouponShopDetails::PURCHASE_STATUS_PURCHASED) {
                        $purchaseStatus = "Purchased";
                    } elseif ($data['purchase_status'] == GrouponShopDetails::PURCHASE_STATUS_LICENSEEXPIRE) {
                        $purchaseStatus = "Licence Expired";
                    }
                    $expireDate = $data['expire_date'] ? : 'not started yet';
                    return $purchaseStatus . "<br>" . "($expireDate)";
                },
                'filter' => [GrouponShopDetails::PURCHASE_STATUS_TRAIL=>'Trial', GrouponShopDetails::PURCHASE_STATUS_TRAILEXPIRE=>'Trial Expired', GrouponShopDetails::PURCHASE_STATUS_PURCHASED=>'Purchased', GrouponShopDetails::PURCHASE_STATUS_LICENSEEXPIRE=>'Licence Expired']
            ],
            //'expire_date',
            /*'ip_address',
            'last_login',*/
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {link} {disable}',
                'buttons' => [
                    'link' => function ($url,$model,$key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-log-in"> </span>',
                            "https://apps.cedcommerce.com/marketplace-integration/groupon/site/managerlogin?ext=".$model->merchant_id."&enter=true",
                            ['title' => 'Login As', 'target'=>'__blank']
                        );
                        return '<a data-pjax="0" href="">Login as</a>';
                    },
                    'view' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open jet-data"> </span>',  
                            $url,
                            ['title' => 'View User']
                        );
                    },
                    'update' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"> </span>',
                            ['/grouponshopdetails/update','id'=>$model->id],
                            ['title' => 'Edit User']
                        );
                    },
                    /*'disable' => function ($url,$model)
                    {
                        if($model->user_status == Merchant::STATUS_ACTIVE) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-remove"> </span>',  
                                ['/user/disable','id'=>$model->merchant_id],
                                ['title' => 'Disable User']
                            );
                        }
                        elseif($model->user_status == Merchant::STATUS_DELETED) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-ok"> </span>',  
                                ['/user/enable','id'=>$model->merchant_id],
                                ['title' => 'Enable User']
                            );
                        }
                        
                    }*/

                ],
            ],
            'shop_json'
        ],
    ]); ?>

</div>
