<?php

use backend\models\PricefallsShopDetails;
use backend\models\PricefallsShopDetailsSearch;
use dosamigos\datepicker\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\modules\pricefalls\components\Data;
use common\models\MERCHANT;




/* @var $this yii\web\View */
/* @var $searchModel backend\models\pricefallsShopDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pricefalls Shop Details';
$this->params['breadcrumbs'][] = $this->title;

?>
<head>
    <style type="text/css">
        .container{
            margin-left: 0;
        }
        .table > tbody > tr.review > td, .table > tbody > tr.review > th, .table > tbody > tr > td.review, .table > tbody > tr > th.review, .table > tfoot > tr.review > td, .table > tfoot > tr.review > th, .table > tfoot > tr > td.review, .table > tfoot > tr > th.review, .table > thead > tr.review > td, .table > thead > tr.review > th, .table > thead > tr > td.review, .table > thead > tr > th.review {
            background-color: #ffffdc;
        }

        .table > tbody > tr.error > td, .table > tbody > tr.error > th, .table > tbody > tr > td.error, .table > tbody > tr > th.error, .table > tfoot > tr.error > td, .table > tfoot > tr.error > th, .table > tfoot > tr > td.error, .table > tfoot > tr > th.error, .table > thead > tr.error > td, .table > thead > tr.error > th, .table > thead > tr > td.error, .table > thead > tr > th.error {
            background-color: #FFB9BB;
        }
    </style>
</head>
<div class="walmart-shop-details-index">


    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'id'=>"pricefalls_extention_details",
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']",
        'pager' => [
            'class' => \liyunfang\pager\LinkPager::className(),
            'pageSizeList' => [25,50,100],
            'pageSizeOptions' => ['class' => 'form-control','style' => 'display: none;width:auto;margin-top:0px;'],
            'maxButtonCount'=>5,
        ],
        'rowOptions'=>function ($model){
            if ($model->install_status=='0'){
                return ['class'=>'error'];
            }elseif (($model->purchase_status=='License Expired')||($model->purchase_status=='Trial Expired')){
                return ['class'=>'danger'];
            }elseif ($model->purchase_status=='Purchased'){
                return ['class'=>'success'];
            }elseif ($model->purchase_status=='Not Purchase'){
                return ['class'=>'review'];
            }

        },
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($data)
                {
                    return ['value' => $data['merchant_id']];
                },
            ],
            /* [
                 'attribute'=>'merchant_id',
                 'label'=>'Merchant Id',
                 'value'=>'pricefallsExtensionDetails.merchant_id',
             ],*/
            //'merchant_id',
             
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {link} {disable}',
                'buttons' => [
                    'link' => function ($url,$model,$key) {
                     
                        $managerLoginUrl=Yii::getAlias("@webpricefallsurl/site/managerlogin?ext=".$model['merchant_id']."&&enter=true");
                        return '<a data-pjax="0" href="'.$managerLoginUrl.'"><span class="glyphicon glyphicon-log-in"></span></a>';
                    },
                    'view' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open jet-data"> </span>',  ['/pricefallsshopdetails/view','id'=>$model->id]

                        );
                    },
                    'update' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"> </span>',['/pricefallsshopdetails/update','id'=>$model->id]
                        );
                    },
                    'disable' => function ($url,$model) {if($model['merchant']->status == MERCHANT::STATUS_ACTIVE) {
                        return Html::a( '<span class="glyphicon glyphicon-remove"> </span>',
                            ['/merchant/disable','id'=>$model['merchant']->id],
                            ['title' => 'Disable User'] );
                          } elseif($model['merchant']->status == MERCHANT::STATUS_DELETED) {
                             return Html::a( '<span class="glyphicon glyphicon-ok"> </span>',
                                 ['/merchant/enable','id'=>$model['merchant']->id],
                                 ['title' => 'Enable User'] ); }
                            }


                ],
            ],
           [
                'attribute' => 'merchant_id',
                'label' => 'Merchant Id',
                'value' => function($data){
                    return "<a href='javascript:void(0)' onclick=getMerchant(".$data['merchant_id'].")>".$data['merchant_id']."</a>";
                },
                'format'=>'raw',
            ],
            [
                'attribute' =>'shopurl',
                'value'=>'merchant.shopurl',
            ],
            [
                'attribute' =>'shopname',
                'value'=>'merchant.shopname',
            ],
            [
                'attribute' =>'email',
                'value'=>'merchant.email',
            ],
            'last_login_time',
            'last_login_IP',
           // 'merchant.shopname',
            //'merchant.email:email',
            //'pricefallsExtensionDetail.install_date',
            
          /*  [
                'label'=>'PUBLISHED',
                'value' => function($data){
                  $result = Yii::$app->db2->createCommand("SELECT count(*) as `count` FROM  `pricefalls_product_variants` WHERE `merchant_id`=" .$data['merchant_id']. " AND `status`=PUBLISHED")->queryOne;

                    return $result['count'];
                },
                'format'=>'raw',
            ],
            [
                'label'=>'UNPUBLISHED',
                'value' => function($data){
                  $result = Yii::$app->db2->createCommand("SELECT count(*) as `count` FROM  `pricefalls_product_variants` WHERE `merchant_id`=" .$data['merchant_id']. " AND `status`=UNPUBLISHED")->queryOne;

                    return $result['count'];
                },
                'format'=>'raw',
            ],
       [
                'label'=>'Revenue',
                'value' => function($data){
                    $result1 = Yii::$app->getDb()->createCommand("SELECT `order_total` FROM `pricefalls_order_details` WHERE `status` = 'completed' AND `merchant_id`=$data->merchant_id")->queryAll();
                    $total=0;
                    foreach ($result1 as $val)
                    {

                        $total=$total+$val['order_total'];

                    }

                    return (float)$total;
                },
                'format'=>'raw',
            ],
*/
              
            [
                'attribute'=>'install_date',
                'format'=>'raw',
                'label'=>'Install Date',
                'value'=>'install_date',
                'filter'=>"<strong>From :</strong> ".DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'install_date',
                        'clientOptions'=>[
                            'autoclose'=>true,
                            'format'=>'yyyy-mm-dd',
                        ]
                    ])."<strong>To :</strong>".DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'install_date2',
                        'clientOptions'=>[
                            'autoclose'=>true,
                            'format'=>'yyyy-mm-dd',
                        ]
                    ]),
            ],
            //'pricefallsExtensionDetail.expire_date',
            [
                'attribute'=>'expire_date',
                'format'=>'raw',
                'label'=>'Expire Date',
                'value'=>'expire_date',
                'filter'=>"<strong>From :</strong> ".DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'expire_date',
                        'clientOptions'=>[
                            'autoclose'=>true,
                            'format'=>'yyyy-mm-dd',
                        ]
                    ])."<strong>To :</strong>".DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'expire_date2',
                        'clientOptions'=>[
                            'autoclose'=>true,
                            'format'=>'yyyy-mm-dd',
                        ]
                    ]),
            ],
            //'pricefallsExtensionDetail.status',
            [
                'attribute'=>'purchase_status',
                'label'=>'Purchase status',
                'value'=>'purchase_status',

                'filter'=>array("Purchased"=>"Purchased","Not Purchase"=>"Not Purchase","License Expired"=>"License Expired","Trial Expired"=>"Trial Expired"),


            ],
            [
                'format'=>'raw',
                'attribute' => 'install_status',
                'value'=>function ($data){

                    if ($data->install_status == "1") {
                        return "install";
                    }
                    else if ($data->install_status =="0") {
                        return "uninstall";
                    }
                },

                'filter'=>array(1=>"install",0=>"uninstall"),
            ],
            [
                'attribute'=>'uninstall_date',
                'label'=>'uninstall_date',
                'value'=>'uninstall_date',

                'filter'=>"<strong>From :</strong> ".DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'uninstall_date',
                        'clientOptions'=>[
                            'autoclose'=>true,
                            'format'=>'yyyy-mm-dd',
                        ]
                    ])."<strong>To :</strong>".DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'uninstall_date2',
                        'clientOptions'=>[
                            'autoclose'=>true,
                            'format'=>'yyyy-mm-dd',
                        ]
                    ]),

            ],
            // 'token',
            // 'currency',
            //'status',

        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
<script type="text/javascript">
    function selectPage(node){
        var value=$(node).val();
        $('#pricefalls_extention_details').children('select.form-control').val(value);
    }
</script>