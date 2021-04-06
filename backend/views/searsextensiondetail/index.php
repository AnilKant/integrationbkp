<?php
// use backend\models\JetShopDetailsSearch;
use common\models\User;
use frontend\modules\sears\components\Dashboard\Earninginfo;
use frontend\modules\sears\components\Dashboard\OrderInfo;
use frontend\modules\sears\components\Dashboard\Productinfo;
use frontend\modules\sears\components\Searsappdetails;
use common\components\IntegrationLoginHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$prodHelper = new Productinfo();
$this->title = 'Sears Shop Details';
$this->params['breadcrumbs'][] = $this->title;
$helper = new IntegrationLoginHelper();
$loginUrl = $helper->getLoginasMerchantUrl('sears');
?>
<div class="sears-extension-detail-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::beginForm(['jetshopdetails/bulk'],'post',['id'=>'jet_extention_detail']);?>
    <?= Html::dropDownList('bulk_name', null, [''=>'-- select bulk action --','export'=>'Export Csv', 'staff-account' => 'Create staff account'], ['id'=>'jet_bulk_select','class'=>'form-control','data-step'=>'2','data-intro'=>"Select the BULK ACTION you want to operate.",'data-position'=>'bottom']);
    ?>
    <?=Html::submitButton('Submit', ['class'=>'btn btn-primary export_csv_submit','value'=>'submit']);?>

    <div class="list-page" style="float:right">
        Show per page
        <select onchange="selectPage(this)" class="form-control" style="display: inline-block; width: auto; margin-top: 0px; margin-left: 5px; margin-right: 5px;" name="per-page">
            <option value="25" <?php if(isset($_GET['per-page']) && $_GET['per-page']==25){echo "selected=selected";}?>>25</option>
            <option <?php if(!isset($_GET['per-page'])){echo "selected=selected";}?> value="50">50</option>
            <option value="100" <?php if(isset($_GET['per-page']) && $_GET['per-page']==100){echo "selected=selected";}?> >100</option>
        </select>
        Items
    </div>
    <div class="clearfix"></div>
    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
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
            if ($model->app_status == 'uninstall') {
                return ['class' => 'error'];
            } elseif (($model->status == 'License Expired') || ($model->status == 'Trial Expired')) {
                return ['class' => 'danger'];
            } elseif ($model->status == 'Purchased') {
                return ['class' => 'success'];
            }

        },
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($data) {
                    return ['value' => $data['merchant_id'], 'class' => 'bulk_checkbox', 'headerOptions' => ['id' => 'checkbox_header', 'data-step' => '1', 'data-intro' => "Select merchants to Export CSV", 'data-position' => 'right']];
                },
            ],
            [
                'label' => 'M-ID',
                'attribute' => 'merchant_id',
            ],
            [
                'label' => 'Shop Url',
                'attribute' => 'shop_url',
                'value' => 'sears_shop_details.shop_url',
            ],
            [
                'label' => 'Email',
                'attribute' => 'email',
                'value' => 'sears_shop_details.email',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {link}',
                'buttons' => [
					'link' => function ($url,$model,$key) use ($loginUrl) {
						return Html::a(
							'<span class="glyphicon glyphicon-log-in"> </span>',
							$loginUrl.$model['merchant_id'],
							['title' => 'Login As']
						);
						return '<a data-pjax="0" href="">Login as</a>';
					},
                   /* 'disable' => function ($url, $model) {
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

                    }*/
                ],
            ],
            [
                'label' => 'status',
                'attribute' => 'status',
                'filter' => array("Purchased" => "Purchased", "Not Purchase" => "Not Purchase", "License Expired" => "License Expired", "Trial Expired" => "Trial Expired"),
            ],
            [
                'format' => 'raw',
                'attribute' => 'app_status',
                'filter' => ["install" => "Installed", "uninstall" => "UnInstalled"],
                'value' => function ($data) {
                    if ($data->app_status == 'install') {
                        return "Installed";
                    }
                    if ($data->app_status == 'uninstall') {
                        return "UnInstalled";
                    }
                },
            ],
            /*[
                'label' => 'PUBLISHED',
                'value' => function ($data) use ($prodHelper) {
                    return $prodHelper->getPublishedProducts($data->merchant_id);
                },
                'headerOptions' => ['style' => 'min-width: 140px;'],
                'filter' => 'From : <input class="form-control" name="JetShopDetailsSearch[live_from]" type="text" value="' . $searchModel->live_from . '"/><br/>To: <input class="form-control" name="JetShopDetailsSearch[live_to]" type="text" value="' . $searchModel->live_to . '"/>',
                'format' => 'raw',
            ],
            [
                'label' => 'ITEM PROCESSING',
                'value' => function ($data) use ($prodHelper) {
                    return $prodHelper->getUnpublishedProducts($data->merchant_id);
                },
                'headerOptions' => ['style' => 'min-width: 140px;'],
                'filter' => 'From : <input class="form-control" name="JetShopDetailsSearch[review_from]" type="text" value="' . $searchModel->review_from . '"/><br/>To: <input class="form-control" name="JetShopDetailsSearch[review_to]" type="text" value="' . $searchModel->review_to . '"/>',
                'format' => 'raw',
            ],*/
            /*[
                'label' => 'Order',
                'value' => function ($data) {
                    return OrderInfo::getCompletedOrdersCount($data->merchant_id);
                },
                'headerOptions' => ['style' => 'min-width: 140px;'],
                'filter' => 'From : <input class="form-control" name="JetShopDetailsSearch[order_from]" type="text" value="' . $searchModel->order_from . '"/><br/>To: <input class="form-control" name="JetShopDetailsSearch[order_to]" type="text" value="' . $searchModel->order_to . '"/>',
                'format' => 'raw',
            ],
            [
                'label' => 'Revenue',
                'value' => function ($data) {
                    return Earninginfo::getTotalEarning($data->merchant_id);
                },
                'format' => 'raw',
            ],*/
            [
                // 'attribute' => 'config',
                /*'label' => 'Seller id/Config Set',
                'value' => function ($data) {
                    $isSet = Searsappdetails::isAppConfigured($data->merchant_id);
                    if ($isSet) {
                        return '<a href="http://sears.com/seller/' . $isSet . '" target="_blank">' . $isSet . '</a>';
                    } else {
                        return "No";
                    }
                },
                'format' => 'raw',*/
                'attribute' => 'config',
                'value' => function($data){
                    if($data['config']){
                        return '<a href="http://sears.com/seller/' . $data['config'] . '" target="_blank">' . $data['config'] . '</a>';
                        // return $data['config'];
                    } else {
                        return "No";
                    }
                },
                'filter' => ['no'=>'No', 'yes'=>'Yes'],
                'format' => 'raw',

            ],
            'mobile',
            [
                'label' => 'Installed On',
                'attribute' => 'install_date',
            ],

            [
                'label' => 'Expired On',
                'attribute' => 'expire_date',
            ],

            [
                'label' => 'Uninstalled On',
                'attribute' => 'uninstall_date',
            ],


            [
                'label' => 'Last Login IP',
                'attribute' => 'last_login_ip',
                'value' => function ($data) {
                    return $data['sears_shop_details']['last_login_ip'];
                }
            ],
            [
                'label' => 'Last Login Time',
                'attribute' => 'last_login_time',
                'value' => function ($data) {

                    return $data['sears_shop_details']['last_login_time'];
                }
            ],
            'panel_username',
            'panel_password',
            [
                'attribute' => 'user_status',
            ],

        ],
    ]); ?>
    <?php if (isset($this->assetBundles)): ?>
        <?php unset($this->assetBundles['yii\web\JqueryAsset']); ?>
    <? endif; ?>
    <?php Pjax::end(); ?>
    <?= Html::endForm(); ?>
</div>
<script type="text/javascript">
    function selectPage(node) {
        var value = $(node).val();
        $('#jet_shop_details').children('select.form-control').val(value);
    }
    $('.export_csv_submit').click(function (event) {
        if ($("input:checkbox:checked.bulk_checkbox").length == 0) {
            alert('please select merchant id to perform bulk action');
            event.preventDefault();
        }
    });
</script>
