<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use backend\components\Data;
use common\components\IntegrationLoginHelper;



/* @var $this yii\web\View */
/* @var $searchModel backend\models\bonanzaShopDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bonanza Shop Details';
$this->params['breadcrumbs'][] = $this->title;
$helper = new IntegrationLoginHelper();
$loginUrl = $helper->getLoginasMerchantUrl('bonanza');


$verified_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M4.252 14H4C3.46957 14 2.96086 13.7893 2.58579 13.4142C2.21071 13.0391 2 12.5304 2 12C2 11.4696 2.21071 10.9609 2.58579 10.5858C2.96086 10.2107 3.46957 10 4 10H4.252C4.441 9.266 4.732 8.573 5.108 7.936L4.928 7.757C4.55298 7.38172 4.34241 6.87283 4.3426 6.34229C4.34278 5.81175 4.55372 5.30302 4.929 4.928C5.30428 4.55298 5.81317 4.34241 6.34371 4.3426C6.87425 4.34278 7.38298 4.55372 7.758 4.929L7.936 5.108C8.58028 4.72694 9.27514 4.43877 10 4.252V4C10 3.46957 10.2107 2.96086 10.5858 2.58579C10.9609 2.21071 11.4696 2 12 2C12.5304 2 13.0391 2.21071 13.4142 2.58579C13.7893 2.96086 14 3.46957 14 4V4.252C14.734 4.441 15.427 4.732 16.064 5.108L16.243 4.928C16.6183 4.55298 17.1272 4.34241 17.6577 4.3426C17.9204 4.34269 18.1805 4.39452 18.4232 4.49514C18.6658 4.59575 18.8863 4.74318 19.072 4.929C19.2577 5.11482 19.405 5.3354 19.5054 5.57813C19.6058 5.82087 19.6575 6.08101 19.6574 6.34371C19.6573 6.6064 19.6055 6.86651 19.5049 7.10918C19.4042 7.35184 19.2568 7.57231 19.071 7.758L18.892 7.936C19.269 8.573 19.559 9.266 19.748 10H20C20.5304 10 21.0391 10.2107 21.4142 10.5858C21.7893 10.9609 22 11.4696 22 12C22 12.5304 21.7893 13.0391 21.4142 13.4142C21.0391 13.7893 20.5304 14 20 14H19.748C19.5612 14.7249 19.2731 15.4197 18.892 16.064L19.072 16.243C19.2577 16.4288 19.405 16.6494 19.5054 16.8921C19.6058 17.1349 19.6575 17.395 19.6574 17.6577C19.6573 17.9204 19.6055 18.1805 19.5049 18.4232C19.4042 18.6658 19.2568 18.8863 19.071 19.072C18.8852 19.2577 18.6646 19.405 18.4219 19.5054C18.1791 19.6058 17.919 19.6575 17.6563 19.6574C17.3936 19.6573 17.1335 19.6055 16.8908 19.5049C16.6482 19.4042 16.4277 19.2568 16.242 19.071L16.064 18.892C15.4197 19.2731 14.7249 19.5612 14 19.748V20C14 20.5304 13.7893 21.0391 13.4142 21.4142C13.0391 21.7893 12.5304 22 12 22C11.4696 22 10.9609 21.7893 10.5858 21.4142C10.2107 21.0391 10 20.5304 10 20V19.748C9.27514 19.5612 8.58028 19.2731 7.936 18.892L7.757 19.072C7.57118 19.2577 7.3506 19.405 7.10787 19.5054C6.86513 19.6058 6.60499 19.6575 6.34229 19.6574C6.0796 19.6573 5.81949 19.6055 5.57682 19.5049C5.33416 19.4042 5.11369 19.2568 4.928 19.071C4.74231 18.8852 4.59504 18.6646 4.4946 18.4219C4.39415 18.1791 4.3425 17.919 4.3426 17.6563C4.34269 17.3936 4.39452 17.1335 4.49514 16.8908C4.59575 16.6482 4.74318 16.4277 4.929 16.242L5.108 16.064C4.72694 15.4197 4.43877 14.7249 4.252 14V14ZM9 10L7 12L11 16L17 10L15 8L11 12L9 10Z" fill="#03AD33"/>
</svg>';
$not_verified_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M4.252 14H4C3.46957 14 2.96086 13.7893 2.58579 13.4142C2.21071 13.0391 2 12.5304 2 12C2 11.4696 2.21071 10.9609 2.58579 10.5858C2.96086 10.2107 3.46957 10 4 10H4.252C4.441 9.266 4.732 8.573 5.108 7.936L4.928 7.757C4.55298 7.38172 4.34241 6.87283 4.3426 6.34229C4.34278 5.81175 4.55372 5.30302 4.929 4.928C5.30428 4.55298 5.81317 4.34241 6.34371 4.3426C6.87425 4.34278 7.38298 4.55372 7.758 4.929L7.936 5.108C8.58028 4.72694 9.27514 4.43877 10 4.252V4C10 3.46957 10.2107 2.96086 10.5858 2.58579C10.9609 2.21071 11.4696 2 12 2C12.5304 2 13.0391 2.21071 13.4142 2.58579C13.7893 2.96086 14 3.46957 14 4V4.252C14.734 4.441 15.427 4.732 16.064 5.108L16.243 4.928C16.6183 4.55298 17.1272 4.34241 17.6577 4.3426C17.9204 4.34269 18.1805 4.39452 18.4232 4.49514C18.6658 4.59575 18.8863 4.74318 19.072 4.929C19.2577 5.11482 19.405 5.3354 19.5054 5.57813C19.6058 5.82087 19.6575 6.08101 19.6574 6.34371C19.6573 6.6064 19.6055 6.86651 19.5049 7.10918C19.4042 7.35184 19.2568 7.57231 19.071 7.758L18.892 7.936C19.269 8.573 19.559 9.266 19.748 10H20C20.5304 10 21.0391 10.2107 21.4142 10.5858C21.7893 10.9609 22 11.4696 22 12C22 12.5304 21.7893 13.0391 21.4142 13.4142C21.0391 13.7893 20.5304 14 20 14H19.748C19.5612 14.7249 19.2731 15.4197 18.892 16.064L19.072 16.243C19.2577 16.4288 19.405 16.6494 19.5054 16.8921C19.6058 17.1349 19.6575 17.395 19.6574 17.6577C19.6573 17.9204 19.6055 18.1805 19.5049 18.4232C19.4042 18.6658 19.2568 18.8863 19.071 19.072C18.8852 19.2577 18.6646 19.405 18.4219 19.5054C18.1791 19.6058 17.919 19.6575 17.6563 19.6574C17.3936 19.6573 17.1335 19.6055 16.8908 19.5049C16.6482 19.4042 16.4277 19.2568 16.242 19.071L16.064 18.892C15.4197 19.2731 14.7249 19.5612 14 19.748V20C14 20.5304 13.7893 21.0391 13.4142 21.4142C13.0391 21.7893 12.5304 22 12 22C11.4696 22 10.9609 21.7893 10.5858 21.4142C10.2107 21.0391 10 20.5304 10 20V19.748C9.27514 19.5612 8.58028 19.2731 7.936 18.892L7.757 19.072C7.57118 19.2577 7.3506 19.405 7.10787 19.5054C6.86513 19.6058 6.60499 19.6575 6.34229 19.6574C6.0796 19.6573 5.81949 19.6055 5.57682 19.5049C5.33416 19.4042 5.11369 19.2568 4.928 19.071C4.74231 18.8852 4.59504 18.6646 4.4946 18.4219C4.39415 18.1791 4.3425 17.919 4.3426 17.6563C4.34269 17.3936 4.39452 17.1335 4.49514 16.8908C4.59575 16.6482 4.74318 16.4277 4.929 16.242L5.108 16.064C4.72694 15.4197 4.43877 14.7249 4.252 14Z" fill="#AD0D03"/>
<path d="M17 8.4285L15.5715 7L12 10.5715L8.4285 7L7 8.4285L10.5715 12L7 15.5715L8.4285 17L12 13.4285L15.5715 17L17 15.5715L13.4285 12L17 8.4285Z" fill="white"/>
</svg>';
?>
<head>
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
<div class="jet-shop-details-index" style="margin:20px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'id' => "bonanza_extention_details",
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
            if ($model->install_status == '0') {
                return ['class' => 'error'];
            } elseif (($model->purchase_status == 'License Expired') || ($model->purchase_status == 'Trial Expired')) {
                return ['class' => 'danger'];
            } elseif ($model->purchase_status == 'Purchased') {
                return ['class' => 'success'];
            } elseif ($model->purchase_status == 'Not Purchase') {
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
                'template' => '{view} {update}{clientdetail} {link}',
                'buttons' => [
                    /*'link' => function ($url, $model, $key) {
                        return '<a data-pjax="0" href="'.Yii::getAlias('@webbonanzaurl').'/site/managerlogin?ext=' . $model['merchant_id'] . '&enter=true">Login as</a>';
                    },*/
                    'link' => function ($url,$model,$key) use ($loginUrl) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-log-in"> </span>',
                            $loginUrl.$model['merchant_id'],
                            ['title' => 'Login As', 'target'=>'__blank']
                        );
                        return '<a data-pjax="0" href="">Login as</a>';
                    },
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"> </span>', ['/bonanzashopdetails/view', 'id' => $model->id]

                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"> </span>', ['/bonanzashopdetails/update', 'id' => $model->id,'class' => 'bonanza-user form-control']
                        );
                    },
                    'clientdetail' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-user"></span>',
                            'javascript:void(0)', ['class' => 'bonanza-user form-control', 'title' => 'Client details', 'data-pjax' => 0, 'onclick' => 'checkclientdetails("' . $model->merchant_id . '")']);

                    },
                ],
            ],
            [
                'attribute'=>'merchant_id',
                'label'=>'Merchant-ID',
                'contentOptions'=>['style'=>'width: 200px;'],
                'value' => function($data){
                    return $data['merchant_id'];
                },
            ],

            [
                'attribute' => 'shopurl',
                'value' => 'merchant.shopurl',
            ],
            [
                'attribute' => 'shopname',
                'value' => 'merchant.shopname',
            ],
            [
                'attribute' =>'email',
                'format' => 'raw',
                'value' => function($data) use($verified_svg , $not_verified_svg){
                    $svg = '';
                    if(!is_null($data->merchant->verified_email)){
                        if($data->merchant->verified_email === 1){
                            $svg = $verified_svg;
                        }elseif($data->merchant->verified_email === 0){
                            $svg = $not_verified_svg;
                        }
                    }
                    return "<div class='email-wrapper'><a href='mailto:".$data['merchant']['email']."'>".$data['merchant']['email']."</a>".$svg."</div>";
                },

            ],
            [
                'attribute' => 'owner_name',
                'value' => 'merchant.owner_name',
            ],
            [
                'label'=>'Is Config',
                'attribute'=>'is_varified',
                'value' => function($data){
                    $query="SELECT `id` FROM  `bonanza_configuration` where `merchant_id`='".$data['merchant_id']."' AND `is_varified`='yes' LIMIT 0,1";
                    $result = Data::integrationSqlRecords($query, 'one','select');
                    return  isset($result['id'])? "yes":"no";
                },
                'headerOptions' => ['style'=>'min-width: 140px;'],
                'filter' => array("yes" => "yes", "no" => "no"),
                'format'=>'raw',
            ],

            [
                'format' => 'raw',
                'attribute' => 'install_status',
                'filter' => array(1 => "install", 0 => "uninstall"),
                'value' => function ($data) {
                    if ($data->install_status == "1") {
                        return "install";
                    } else if ($data->install_status == "0") {
                        return "uninstall";
                    }
                },
            ],
            [
                'attribute' => 'purchase_status',
                'label' => 'Purchase status',
                'value' => 'purchase_status',
                'filter' => array("Purchased" => "Purchased", "Not Purchase" => "Not Purchase", "License Expired" => "License Expired", "Trial Expired" => "Trial Expired"),
            ],

            [
                'attribute' => 'installed_on',
                'format' => 'raw',
                'label' => 'Install Date',
                'value' => 'installed_on',
            ],

            [
                'attribute' => 'expired_on',
                'format' => 'raw',
                'label' => 'Expire Date',
                'value' => 'expired_on',
            ],
            'ip_address',
            [
                'attribute' => 'uninstall_date',
                'label' => 'uninstall_date',
                'value' => 'uninstall_date',
            ],
            'seller_username',
            'seller_password',
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
<div id='view_client_detail' style="display: none"></div>
<script type="text/javascript">
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    function selectPage(node) {
        var value = $(node).val();
        $('#bonanza_extention_details').children('select.form-control').val(value);
    }

    function checkclientdetails(merchant_id) {
        $('#LoadingMSG').show();

        var url = "<?= Data::getUrl('bonanzashopdetails/viewclientdetails') ?>";

        $('#LoadingMSG').show();
        $.ajax({
            method: "post",
            url: url,
            data: {merchant_id: merchant_id, _csrf: csrfToken}
        })
            .done(function (msg) {
                $('#LoadingMSG').hide();
                $('#view_client_detail').html(msg);
                $('#view_client_detail').css("display", "block");
                $('#view_client_detail #myModal').modal('show');
            });
    }
</script>