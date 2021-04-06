<?php

use backend\models\WalmartExtensionDetail;
use backend\models\WalmartShopDetails;
use backend\models\WalmartShopDetailsSearch;
use common\models\JetProduct;
use dosamigos\datepicker\DatePicker;
use backend\components\Data;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\User;
use yii\bootstrap\Modal;
use common\components\IntegrationLoginHelper;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\WalmartShopDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Walmart Shop Details';
$this->params['breadcrumbs'][] = $this->title;
$urlRegister = \yii\helpers\Url::toRoute(['walmartshopdetails/send-message']);
$prevRegister = \yii\helpers\Url::toRoute(['walmartshopdetails/view-prev-msg']);
$urlRegistered = \yii\helpers\Url::toRoute(['walmartshopdetails/view-merchant']);
$urlValidate = \yii\helpers\Url::toRoute(['walmartshopdetails/validate-merchant']);
$view_merchant_product_order = \yii\helpers\Url::toRoute(['walmartshopdetails/get-product-order-data']);
$urlGetOrderSync = \yii\helpers\Url::toRoute(['walmartshopdetails/getsyncorderdata']);

?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        .container {
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
    <?= Html::beginForm(['walmartshopdetails/export'], 'post'); ?>
    <div class="list-page">
        Show per page
        <select onchange="selectPage(this)" class="form-control"
                style="display: inline-block; width: auto; margin-top: 0px; margin-left: 5px; margin-right: 5px;"
                name="per-page">
            <option value="25" <?php if (isset($_GET['per-page']) && $_GET['per-page'] == 25) {
                echo "selected=selected";
            } ?>>25
            </option>
            <option <?php if (!isset($_GET['per-page'])) {
                echo "selected=selected";
            } ?> value="50">50
            </option>
            <option value="100" <?php if (isset($_GET['per-page']) && $_GET['per-page'] == 100) {
                echo "selected=selected";
            } ?> >100
            </option>
        </select>
        Items
        <button style="float: right;" type="button" onclick="window.location='<?= $urlValidate ?>'">Validate Merchant
        </button>
        <a style="float: right;" href="javascript:void(0)" onclick="getOrderSyncData()">Get Order Sync Merchants
        </a>
    </div>


    <?php
    $helper = new IntegrationLoginHelper();
    $loginUrl = $helper->getLoginasMerchantUrl('walmart');

    Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'id' => "jet_extention_details",
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => "select[name='" . $dataProvider->getPagination()->pageSizeParam . "'],input[name='" . $dataProvider->getPagination()->pageParam . "']",
        'pager' => [
            'class' => \liyunfang\pager\LinkPager::className(),
            'pageSizeList' => [25, 50, 100],
            'pageSizeOptions' => ['class' => 'form-control', 'style' => 'display: none;width:auto;margin-top:0px;'],
            'maxButtonCount' => 5,
        ],
        'rowOptions' => function ($model) {
            if ($model->status == 'uninstall') {
                return ['class' => 'error'];
            } elseif (($model->walmartExtensionDetail->status == 'License Expired') || ($model->walmartExtensionDetail->status == 'Trial Expired')) {
                return ['class' => 'danger'];
            } elseif ($model->walmartExtensionDetail->status == 'Purchased') {
                return ['class' => 'success'];
            } elseif ($model->walmartExtensionDetail->status == 'Not Purchase') {
                return ['class' => 'review'];
            }

        },
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($data) {
                    return ['value' => $data['merchant_id']];
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {link} {removeconfigfile}{product}',
                'buttons' => [
                    'link' => function ($url, $model, $key) use ($loginUrl) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-log-in"> </span>',
                            //"https://apps.cedcommerce.com/integration/walmart/site/managerlogin?ext=".$model->merchant_id."&enter=true",
                            $loginUrl . $model->merchant_id,
                            ['title' => 'Login As']
                        );
                        return '<a data-pjax="0" href="">Login as</a>';
                    },
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open jet-data"> </span>',
                            ['/walmartshopdetails/view', 'id' => $model->merchant_id],
                            ['title' => 'View User']
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"> </span>',
                            ['/walmartshopdetails/update', 'id' => $model->merchant_id],
                            ['title' => 'Edit User']
                        );
                    },
                    'disable' => function ($url, $model) {
                        if ($model->user_status == User::STATUS_ACTIVE) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-remove"> </span>',
                                ['/user/disable', 'id' => $model->merchant_id],
                                ['title' => 'Disable User']
                            );
                        } elseif ($model->user_status == User::STATUS_DELETED) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-ok"> </span>',
                                ['/user/enable', 'id' => $model->merchant_id],
                                ['title' => 'Enable User']
                            );
                        }

                    },
                    'removeconfigfile' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"> </span>', ['/walmartshopdetails/removeconfigfile', 'shop' => $model->shop_url], ['title' => 'Remove Config File']
                        );
                    },
                    'product' => function ($url, $model) {
                        $options = ['data-pjax' => 0, 'onclick' => 'clickView(this.id)', 'title' => 'View Product Detail', 'id' => $model->merchant_id];
                        return Html::a(
                            '<span class="glyphicon glyphicon glyphicon-list-alt"> </span>',
                            'javascript:void(0)', $options
                        );
                    },

                ],
            ],
            /* [
                 'attribute'=>'merchant_id',
                 'label'=>'Merchant Id',
                 'value'=>'walmartExtensionDetails.merchant_id',
             ],*/
            [
                'attribute' => 'merchant_id',
                'label' => 'Merchant Id',
                'value' => function ($data) {
                    return "<a href='javascript:void(0)' onclick=getMerchants(" . $data['merchant_id'] . ",'Registration')>" . $data['merchant_id'] . "</a>";
                },
                'format' => 'raw',
            ],
            /*[
                'attribute' => 'merchant_id',
                'label' => 'Merchant Id',
                'format' => 'html',
                'filter' => 'From : <input id="merchant_id" class="form-control" type="text" value="' . $searchModel->merchant_id . '" /><br/>' . 'To : <input class="form-control" type="text"  value="' . $searchModel->merchant_id . '"/>',
                'value' => 'merchant_id',
            ],*/
            'shop_url:url',
            'shop_name',
            'email:email',
            [
                'attribute' => 'user_status',
            ],
            [
                'attribute' => 'status1',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->walmartExtensionDetail->status == "Purchased" || $data->walmartExtensionDetail->status == "License Expired") 
                    {
                        /* return Html::a($data->status,"javascript:void(0)" , ['title'=>'Click this to see Payment Details', 'onclick'=>'getMerchant(".$data->merchant_id.","Payment").$data->status.']);
     */
                       $payment_data = Data::sqlRecords("SELECT plan_type FROM `walmart_recurring_payment` WHERE merchant_id = ".$data->merchant_id." AND `status`='active' AND `plan_type` NOT LIKE '%import charge%' AND `plan_type` NOT LIKE '%importing charge%' AND `plan_type` NOT LIKE '%customized%' AND `plan_type` NOT LIKE '%custom%' ORDER BY `activated_on` DESC","one");

                            if(isset($payment_data['plan_type']) && $payment_data['plan_type']!='')
                            {
                                 return "<a href='javascript:void(0)' onclick=getMerchants(" . $data->merchant_id . ",'Payment')>" . $data->walmartExtensionDetail->status . "</a>".'<br>('.$payment_data['plan_type'].')';
                            }else{
                                 return "<a href='javascript:void(0)' onclick=getMerchants(" . $data->merchant_id . ",'Payment')>" . $data->walmartExtensionDetail->status . "</a>";
                            }
                    }
                    return $data->walmartExtensionDetail->status;
                },
                'filter' => array("Purchased" => "Purchased", "Not Purchase" => "Not Purchase", "License Expired" => "License Expired", "Trial Expired" => "Trial Expired"),
            ],
            [
                'attribute' => 'install_date',
                'format' => 'raw',
                'label' => 'Install Date',
                'value' => 'walmartExtensionDetail.install_date',
                'filter' => "<strong>From :</strong> " . DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'install_date',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]) . "<strong>To :</strong>" . DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'install_date2',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]),
            ],
            //'walmartExtensionDetail.expire_date',
            [
                'attribute' => 'expire_date',
                'format' => 'raw',
                'label' => 'Expire Date',
                'value' => 'walmartExtensionDetail.expire_date',
                'filter' => "<strong>From :</strong> " . DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'expire_date',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]) . "<strong>To :</strong>" . DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'expire_date2',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]),
            ],
            [
                'label' => 'Valid Number',
                'attribute' => 'mobile',
                'value' => function ($data) {
                    if (!is_null($data['mobile'])) {
                        return "<a href='javascript:void(0)' onclick=getMerchant(" . $data['mobile'] . "," . $data['merchant_id'] . ")>" . $data['mobile'] . "</a>";
                    } else {
                        $data['mobile'];
                    }

                },
                'format' => 'raw',
            ],
            [
                'label' => 'Config Set',
                'attribute' => 'config',
                'value' => function ($data) {

                    /*$isSet =  Data::sqlRecords("SELECT `merchant_id` FROM  `walmart_config` WHERE `merchant_id`='".$data['merchant_id']."' AND `data`='client_id' AND `value` !=''","one");*/
                    // print_r($data['config']);die("dfg");
                   
                    if ($data['config'] && $data['config'] == "YES") {
                        return "Yes";
                    } else {
                         return "No";
                    }
                },
                'filter' => ['no' => 'No', 'yes' => 'Yes']
            ],
            [
                'label' => 'Seller Username',
                'attribute' => 'seller_username',
            ],
            [
                'label' => 'Seller Password',
                'attribute' => 'seller_password',
            ],

            //'walmartExtensionDetail.status',

            [
                'format' => 'raw',
                'attribute' => 'status',
                'value' => function ($data) {

                    if ($data->status == "1") {
                        return "install";
                    } else if ($data->status == "0") {
                        return "uninstall";
                    }
                },

                'filter' => array(1 => "install", 0 => "uninstall"),
            ],
            [
                'attribute' => 'uninstall_date',
                'label' => 'uninstall_date',
                'value' => 'walmartExtensionDetail.uninstall_date',

                'filter' => "<strong>From :</strong> " . DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'uninstall_date',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]) . "<strong>To :</strong>" . DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'uninstall_date2',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]),

            ],
            // 'token',
            // 'currency',
            //'status',
            'last_login',
            'ip_address',
            'install_country',
            'shop_data'
        ],
    ]); ?>
    <?php Pjax::end();


    ?>
    <div id="view_merchant_product_order" style="display:none">
    </div>

    <div class="container">
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="text-align: center;">Send Message</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="is_success" style="display: none;"></div>
                        <div class="alert alert-danger" id="is_error" style="display: none;"></div>
                        <div><a href="" target="_blank" id="view_message">View Priveous Message</a></div>
                        <div class="sku_details jet_details_heading">

                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td>
                                        <label>Client Number</label>
                                    </td>
                                    <td>
                                        <input type="text" id="client_number" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Write Your Message Here!</label>

                                    </td>
                                    <td>
                                        <textarea rows="7" cols="80" id="client_message"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-primary" id="send_message"
                                                onclick="sendMessage()">Send Message
                                        </button>
                                    </td>
                                    <td>
                                        <input type="hidden" id="merchant_id">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="merchant_register" style="display:none"></div>
    <div id="sync_order_merchants" style="display:none"></div>

    <script type="text/javascript">
        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        function selectPage(node) {
            var value = $(node).val();
            $('#jet_extention_details').children('select.form-control').val(value);
        }

        function getMerchant(id, mid) {

            $('#myModal').modal('show');
            $('#client_number').val('+' + id + '');
            $('#merchant_id').val(mid);
            var url = '<?=$prevRegister?>' + '?mid=' + mid;
            $('#view_message').attr('href', url);
        }

        function getOrderSyncData() {

            var url = '<?= $urlGetOrderSync ?>';

            $('#LoadingMSG').show();
            $.ajax({
                method: "post",
                url: url,
            })
                .done(function (msg) {
                    // console.log(msg);
                    $('#LoadingMSG').hide();
                    $('#sync_order_merchants').html(msg);
                    $('#sync_order_merchants').css("display", "block");
                    $('#sync_order_merchants #myModal').modal('show');
                });
        }



        function sendMessage() {
            var number = $('#client_number').val();
            var message = $('#client_message').val();
            var mid = $('#merchant_id').val();
            var url = '<?= $urlRegister ?>';
            if (message != '') {
                $.ajax({
                    url: url,
                    method: "post",
                    dataType: 'json',
                    data: {
                        number: number, _csrf: csrfToken, message: message, mid: mid
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#is_error').css('display', 'none');
                            $('#is_success').css('display', 'block');
                            $('#is_success').html('<p>' + data.message + '</p>');

                        }
                        else {
                            $('#is_success').css('display', 'none');
                            $('#is_error').css('display', 'block');
                            $('#is_error').html('<p>' + data.message + '</p>');
                        }
                    },
                    error: function (data) {
                        alert("in error");
                    },
                });
            }
            else {
                $('#is_success').css('display', 'none');
                $('#is_error').css('display', 'block');
                $('#is_error').html('<p>Please Write some message</p>');
            }


        }

        function getMerchants(id, param) {
            var url = '<?= $urlRegistered ?>';
            $('#LoadingMSG').show();
            $.ajax({
                method: "post",
                url: url,
                data: {id: id, param: param, _csrf: csrfToken}
            })
                .done(function (msg) {
                    //console.log(msg);
                    $('#LoadingMSG').hide();
                    $('#merchant_register').html(msg);
                    $('#merchant_register').css("display", "block");
                    $('#merchant_register #myModal').modal('show');
                });
        }

        function validateMerchant() {
            var url = '<?= $urlValidate ?>';
            $('#LoadingMSG').show();
            $.ajax({
                method: "post",
                url: url,
                // data: {id:id,param:param,_csrf : csrfToken }
            })
                .done(function (msg) {
                    $('#LoadingMSG').hide();
                    $('#merchant_validate').html(msg);
                    $('#merchant_validate').css("display", "block");

                });
        }

        function clickView(id) {
            var url = '<?= $view_merchant_product_order; ?>';
            $('#LoadingMSG').show();
            $.ajax({
                method: "post",
                url: url,
                data: {merchant_id: id, _csrf: csrfToken}
            })
                .done(function (msg) {
                    //console.log(msg);
                    $('#LoadingMSG').hide();
                    $('#view_merchant_product_order').html(msg);
                    $('#view_merchant_product_order').css("display", "block");
                    $('#view_merchant_product_order #myModal').modal({
                        keyboard: false,
                        backdrop: 'static'
                    })
                });

        }
    </script>

