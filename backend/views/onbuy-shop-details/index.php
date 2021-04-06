<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Merchant;
use common\components\IntegrationLoginHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OnbuyShopDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Onbuy Shop Details';
$this->params['breadcrumbs'][] = $this->title;
$helper = new IntegrationLoginHelper();
$loginUrl = $helper->getLoginasMerchantUrl('onbuy');
$urlRegister = \yii\helpers\Url::toRoute(['onbuy-shop-details/view-merchant']);

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

<div class="onbuy-shop-details-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <a value="Validate Shop" href="validate-shop"><spanc class="glyphicon glyphicon-retweet"></spanc>Validate Shop</a>

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function ($model){
            if ($model->install_status=='0'){
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
            ['class' => 'yii\grid\SerialColumn'],
          //  ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {link}',
                'buttons' => [
                    'link' => function ($url,$model,$key) use ($loginUrl) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-log-in"> </span>',
                            // "https://apps.cedcommerce.com/marketplace-integration/walmart-canada/site/managerlogin?ext=".$model->merchant_id."&enter=true",
                            $loginUrl.$model->merchant_id,
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
                            ['/onbuy-shop-details/update','id'=>$model->id],
                            ['title' => 'Edit User']
                        );
                    },
                ],
            ],
            //'id',
           // 'merchant_id',
            [
                'attribute' => 'merchant_id',
                'label' => 'Merchant Id',
                'value' => function($data){
                    // return $data['merchant_id'];
                    return "<a href='javascript:void(0)' onclick=getMerchant(".$data->merchant_id.",'Registration')>".$data->merchant_id."</a>";

                },
                'format'=>'raw',
            ],
            [
                'attribute' => 'shopurl',
                'label' => 'Shop',
                'value' => function($data){
                    return '<a target="_blank" href="https://'.$data['shopurl'].'/">'.$data['shopurl'].'</a>';
                },
                'format'=>'raw',
            ],
            'email',
            [
                'attribute'=>'status',
                'label'=>'Config Set',
                'format'=>'raw',
                'value' => function($data)
                {
                    if($data->status=="1")
                    {
                        return "Yes";
                    }else{
                        return "No";
                    }
                },
                'filter'=>array("1"=>"Yes","0"=>"No"),
            ],
            [
                'label'=>'Owner Name',
                'format'=>'raw',
                'value'=> function($data)
                {                    
                    return $data['owner_name'];
                }
            ],
            'verified_mobile',
            //'token',
            // 'install_status',
            [
                'attribute'=>'install_status',
                'label'=>'Install Status',
                'format'=>'raw',
                'value' => function($data)
                {
                    if($data->install_status=="1")
                    {
                        return "Install";
                    }else{
                        return "Uninstall";
                    }
                },
                'filter'=>array("1"=>"Install","0"=>"Uninstall"),
            ],
            'install_date',
            'uninstall_date',
            [
                'label'=>'Purchase Status',
                'attribute' => 'purchase_status',
                'format' => 'html',
                'value' => function($data){
                    // print_r($data);die('die...data');  purchase_status
                    $purchaseStatus = '';
                    if ($data['purchase_status'] == 1) {
                        $purchaseStatus = "Trial";
                    } elseif ($data['purchase_status'] == 2) {
                        $purchaseStatus = "Trial Expired";
                    } elseif ($data['purchase_status'] == 3) {
                        //$purchaseStatus = "Purchased";
                        return "<a href='javascript:void(0)' onclick=getMerchants(".$data->merchant_id.",'Payment')>".'Purchased'."</a>";
                    } elseif ($data['purchase_status'] == 4) {
                        // $purchaseStatus = "Licence Expired";
                        return "<a href='javascript:void(0)' onclick=getMerchants(".$data->merchant_id.",'Payment')>".'License Expired'."</a>";
                    }
                    $expireDate = $data['expire_date'] ? : 'not started yet';
                    return $purchaseStatus . "<br>" . "($expireDate)";
                },
                'filter' => ['1'=>'Trial', '2'=>'Trial Expired', '3'=>'Purchased', '4'=>'Licence Expired']
            ],

            'expire_date',
            [
                'label'=>'Seller Username',
                'attribute' => 'seller_username',
                'format' => 'html',
                'value' => function($data){
                    /*if($data['merchant_id'] == 13447){
                        echo "<pre>";print_r($data);die;
                    }*/
                    return $data['seller_username'];
                }
            ],
            [
                'label'=>'Seller Password',
                'attribute' => 'seller_password',
                'format' => 'html',
                'value' => function($data){
                    return $data['seller_password'];
                }
            ],
            'ip_address',
            'last_login',

        ],
    ]); ?>
</div>
<div id="merchant_register" style="display:none"></div>
<script type="text/javascript">
var csrfToken = $('meta[name="csrf-token"]').attr("content");
    function getMerchant(id,param)
    {
        var url='<?= $urlRegister ?>';
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
    function selectPage(node){
      var value=$(node).val();
      $('#jet_shop_details').children('select.form-control').val(value);
    }
$('.export_csv_submit').click(function(event){
  if($("input:checkbox:checked.bulk_checkbox").length == 0)
  {
    alert('please select merchant id to perform bulk action');
    event.preventDefault();
  }
});
</script>