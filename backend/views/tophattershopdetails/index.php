<?php

use backend\models\TophatterShopDetails;
use backend\models\TophatterShopDetailsSearch;
use dosamigos\datepicker\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\modules\jet\components\Data;
use common\models\MERCHANT;
use common\components\IntegrationLoginHelper;




/* @var $this yii\web\View */
/* @var $searchModel backend\models\tophatterShopDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$urlRegistered = \yii\helpers\Url::toRoute(['tophattershopdetails/view-merchant']);
$helper = new IntegrationLoginHelper();
$loginUrl = $helper->getLoginasMerchantUrl('tophatter');
$this->title = 'Tophatter Shop Details';
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
        'id'=>"tophatter_extention_details",
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
            if ($model->install_status=='0')
            {
                return ['class'=>'error'];
            }
            elseif (($model->purchase_status=='2')||($model->purchase_status=='3'))
            {
                return ['class'=>'danger'];
            }
            elseif ($model->purchase_status=='1')
            {
                return ['class'=>'success'];
            }
            elseif ($model->purchase_status=='0')
            {
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
                 'value'=>'tophatterExtensionDetails.merchant_id',
             ],*/
             [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{login}{update}',
                'buttons' => [
                    'login' => function ($url,$model,$key) use ($loginUrl) {
                       /* return '<a data-pjax="0" target="_blank" href="'.Yii::getAlias("@webtophatterurl").'/site/managerlogin?ext='.$model['merchant_id'].'&&enter=true"><span class="glyphicon glyphicon-log-in"></span></a>';*/
                         return Html::a(
                            '<span class="glyphicon glyphicon-log-in"> </span>',
                            //"https://apps.cedcommerce.com/marketplace-integration/wish/site/managerlogin?ext=".$model->merchant_id."&enter=true",
                            $loginUrl.$model['merchant_id'],
                            ['title' => 'Login As', 'target'=>'__blank']
                        );
                        return '<a data-pjax="0" href="">Login as</a>';
                    },
                    'update' => function ($url,$model)
                    {
                        return Html::a(
                            '<span data-pjax="0" class="glyphicon glyphicon-plus-sign"> </span>',['/tophattershopdetails/update','id'=>$model->id]
                        );
                    },
                  /*  'disable' => function ($url,$model) {if($model['merchant']->status == MERCHANT::STATUS_ACTIVE) {
                        return Html::a( '<span class="glyphicon glyphicon-remove"> </span>',
                            ['/merchant/disable','id'=>$model['merchant']->id],
                            ['title' => 'Disable User'] );
                          } elseif($model['merchant']->status == MERCHANT::STATUS_DELETED) {
                             return Html::a( '<span class="glyphicon glyphicon-ok"> </span>',
                                 ['/merchant/enable','id'=>$model['merchant']->id],
                                 ['title' => 'Enable User'] ); }
                            }*/


                ],
            ],
            
            [
                'attribute' => 'merchant_id',
                'label' => 'Merchant Id',
                'value' => function($data){
                    return "<a href='javascript:void(0)' onclick=getMerchants(".$data['merchant_id'].",'Registration')>".$data['merchant_id']."</a>";
                },
                'format'=>'raw',
            ],
            [
                'attribute' =>'shopurl',
                'value' => function($data){
                    return "<a data-pjax='0' href=https://".$data['merchant']['shopurl']." target='_blank'>".$data['merchant']['shopurl']."</a>";
                },
                'format'=>'raw',
                //'value'=>'merchant.shopurl',
            ],
           /* [
                'attribute' =>'shopname',
                'value'=>'merchant.shopname',
            ],*/
            [
                'attribute' =>'email',
                'value' => function($data){
                    return "<a href=mailto:".$data['merchant']['email']." target='_blank'>".$data['merchant']['email']."</a>";
                },
                'format'=>'raw',
            ],
           
            [
                'attribute' => 'purchase_status',
                'label'=>'Purchase status',
                 'format'=>'raw',
                'value' => function($data){

                    if($data->purchase_status==0)
                    {
                        return "<a href='javascript:void(0)' onclick=getMerchants(".$data->merchant_id.",'Payment')>".'Recurring Trial'."</a>";
                    }
                   elseif($data->purchase_status==1)
                    {
                      return "<a href='javascript:void(0)' onclick=getMerchants(".$data->merchant_id.",'Payment')>".'Purchased'."</a>";
                    }
                    elseif ($data->purchase_status==2) 
                    {
                        return "Trial Expired";
                    }
                   elseif ($data->purchase_status==3) 
                    {
                       return "<a href='javascript:void(0)' onclick=getMerchants(".$data->merchant_id.",'Payment')>".'License Expired'."</a>";
                    }
                   elseif ($data->purchase_status==4) 
                    {
                        return "Not Purchased";
                    }
                    
                
               },     

               'filter'=>array("4"=>"Not Purchased","0"=>"Recurring Trial","1"=>"Purchased","2"=>"Trial Expired","3"=>"License Expired"),
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
                'label'=>'Config Set',
                'attribute'=>'config_path',
                'format'=>'raw',
                'value' => function($data){
                    $isSet = Yii::$app->admin->createCommand("SELECT `merchant_id` FROM  `tophatter_configuration_setting` where `merchant_id`='".$data['merchant_id']."' AND `config_path`='access_token'")->queryOne();
                    
                   return  isset($isSet['merchant_id'])? "yes":"no";
                },
                /*'value' => function($data){
                    print_r($data);die("ss");
                    if($data['config_path']){
                        return "Yes";
                    } else {
                        return "No";
                    }
                },*/
                 'filter'=>array("yes" =>"yes","no"=>"no"),
            ],
            [
                'label'=>'ENABLED',
                'value' => function($data){
                  $result = Yii::$app->admin->createCommand("SELECT count(*) as `count` FROM  `tophatter_product_variants` WHERE `merchant_id`=" .$data['merchant_id']. " AND `status`='ENABLED'")->queryOne();

                    return $result['count'];
                },
                'format'=>'raw',
            ],
            [
                'label'=>'Orders',
                'value' => function($data){
                     $result = Yii::$app->admin->createCommand( "SELECT count(*) as `count` FROM `tophatter_orders` WHERE `merchant_id` =".$data['merchant_id'])->queryOne();
                    return $result['count'];
                },
                'format'=>'raw',
            ],
            'last_login_time',
            'last_login_IP',
            
              
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
            //'tophatterExtensionDetail.expire_date',
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
            //'tophatterExtensionDetail.status',
            
            [
                'attribute'=>'uninstall_dates',
                'label'=>'uninstall_on',
                'value'=>'uninstall_dates',

                'filter'=>"<strong>From :</strong> ".DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'uninstall_dates',
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
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
<div id="merchant_register" style="display:none"></div>
    <script type="text/javascript">
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    function selectPage(node){
        var value=$(node).val();
        $('#tophatter_extention_details').children('select.form-control').val(value);
    }
    function getMerchants(id,param){
        var url='<?= $urlRegistered ?>';
        $('#LoadingMSG').show();
        $.ajax({
            method: "post",
            url: url,
            data: {id:id,param:param,_csrf : csrfToken }
        })
        .done(function(msg) {
            //console.log(msg);
            $('#LoadingMSG').hide();
            $('#merchant_register').html(msg);
            $('#merchant_register').css("display","block");
            $('#merchant_register #myModal').modal('show');
        });
    }
</script>