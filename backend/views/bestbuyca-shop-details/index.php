<?php

use common\models\Merchant;
use dosamigos\datepicker\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\IntegrationLoginHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\BestbuycaShopDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bestbuyca Shop Details';
$this->params['breadcrumbs'][] = $this->title;
$merchant_view=\yii\helpers\Url::toRoute(['bestbuyca-shop-details/merchant-view']);
$helper = new IntegrationLoginHelper();
$loginUrl = $helper->getLoginasMerchantUrl('bestbuyca');
?>
<div class="bestbuyca-shop-details-index">

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
        <a value="Validate Shop" href="validate-shop"><spanc class="glyphicon glyphicon-retweet"></spanc>Validate Shop</a>
        <?= GridView::widget([
            'id'=>"bestbuyca_shop_details",
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
                }elseif (($model->purchase_status==3)||($model->purchase_status==4)){
                    return ['class'=>'danger'];
                }elseif ($model->purchase_status==2){
                    return ['class'=>'success'];
                }elseif ($model->purchase_status==1){
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
                    'template' => '{update} {link}',
                    'buttons' => [
                        'link' => function ($url,$model,$key) use($loginUrl) {

                            /*$managerLoginUrl=Yii::getAlias("@webbestbuycaurl/site/managerlogin?ext=".$model['merchant_id']."&enter=true");*/
                            /*   print_r($managerLoginUrl);die;*/
                            $managerLoginUrl=$loginUrl.$model['merchant_id'];
                            return '<a data-pjax="0" href="'.$managerLoginUrl.'" target="_blank"><span class="glyphicon glyphicon-log-in"></span></a>';
                        },
                        'view' => function ($url,$model)
                        {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open jet-data"> </span>',  ['/bestbuyca-shop-details/view','id'=>$model->id]

                            );
                        },
                        'update' => function ($url,$model)
                        {
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"> </span>',['/bestbuyca-shop-details/update','id'=>$model->id]
                            );
                        },
                       /* 'disable' => function ($url,$model) {if($model['merchant']->status == MERCHANT::STATUS_ACTIVE) {
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
                        /*print_r($data);die;*/
                        return "<a href='javascript:void(0)' onclick=getMerchant(".$data['merchant_id'].")>".$data['merchant_id']."</a>";
                    },
                    'format'=>'raw',
                ],
                [
                    'attribute' =>'shopurl',
                    'value'=>function($data){

                        $shop_url=$data['merchant']['shopurl'];
                        $shop_url="https://".$shop_url;
                        return "<a href='".$shop_url."' target='_blank'>".$data['merchant']['shopurl']."</a>";
                    }//'merchant.shopurl',
                    ,
                    'format'=>'raw',
                ],
                [
                    'attribute' =>'shopname',
                    'value'=>'merchant.shopname',
                ],
                [
                    'attribute' =>'email',
                    /*'value'=>'merchant.email',*/
                    'value' => function($data){
                        return "<a href='mailto:".$data['merchant']['email']."'>".$data['merchant']['email']."</a>";
                    },
                    'format' => 'raw'
                ],
                [
                    'label'=>'Config Set',
                    'attribute'=>'config_path',
                    'format'=>'raw',
                    'value' => function($data){

                        $isSet = Yii::$app->admin->createCommand("SELECT `step` FROM  `bestbuyca_installation` where `merchant_id`='".$data['merchant_id']."' AND `step` > 2")->queryOne();

                        return  isset($isSet['step'])? "yes":"no";
                    },
                    'filter'=>array("yes" =>"yes","no"=>"no"),
                ],

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
                [
                    'attribute'=>'import_renew_till',
                    'format'=>'raw',
                    'label'=>'Import Renew Date',
                    'value'=>'import_renew_till',
                    'filter'=>"<strong>From :</strong> ".DatePicker::widget([
                            'model'=>$searchModel,
                            'attribute'=>'import_renew_till',
                            'clientOptions'=>[
                                'autoclose'=>true,
                                'format'=>'yyyy-mm-dd',
                            ]
                        ]),
                ],
                //'pricefallsExtensionDetail.status',["0"=>"Not Purchased","1"=>"Purchased","2"=>"Trial Expired","3"=>"License Expired"],['prompt'=>'Select option']
                [
                    'attribute'=>'purchase_status',
                    'label'=>'Purchase status',
                    'value'=>function($data){
                        /* if($_SERVER['REMOTE_ADDR']=='192.168.0.128'){*/
                        if($data['purchase_status']==1){
                            //return 'Not Purchased';
                            return 'Trail';
                        }elseif($data['purchase_status']==2){
                            return 'Purchased';
                        }elseif($data['purchase_status']==3){
                            return 'Trial Expired';
                        }elseif($data['purchase_status']==4){
                            return 'License Expired';
                        }
                        /* }*/
                        //if($data[''])
                    },

                    'filter'=>array("2"=>"Purchased","1"=>"Not Purchase","4"=>"License Expired","3"=>"Trial Expired"),


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
                 [
                    'attribute' =>'maximum_prod_limit',
                    'value'=>'maximum_prod_limit',
                ],
                [
                    'attribute' =>'seller_username',
                    'value'=>'seller_username',
                ],

                [
                    'attribute' =>'seller_password',
                    'value'=>'seller_password',
                ],
                'last_login_time',
                'last_login_ip',

            ],
        ]); ?>
        <?php Pjax::end(); ?>

    </div>
</div>
    <div id="merchant_register" style="display: none;"></div>

    <script type="text/javascript">

        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        function getMerchant(id){
            var url='<?=$merchant_view;?>';
            $('#LoadingMSG').show();
            $.ajax({
                url:url,
                data:{merchant_id:id,_csrf : csrfToken },
                method:'post'
            }).done(function(msg) {
                //console.log(msg);
                $('#LoadingMSG').hide();
                $('#merchant_register').html(msg);
                $('#merchant_register').css("display","block");
                $('#merchant_register #myModal').modal('show');
            });
        }
        function selectPage(node){
            var value=$(node).val();
            $('#bestbuyca_shop_details').children('select.form-control').val(value);
        }
    </script>
