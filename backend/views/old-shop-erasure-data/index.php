<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\etsy\models\ShopErasureDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deleted Merchants Old App';
$this->params['breadcrumbs'][] = $this->title;
$merchant_view=\yii\helpers\Url::toRoute(['shop-erasure-data/merchant-view']);
?>
<div class="shop-erasure-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="shop-erasure-data-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'merchant_id',
                'label' => 'Merchant Id',
                'value' => function($data){
                    /*print_r($data);die;*/
                    return "<a href='javascript:void(0)' onclick=getMerchant(".$data['merchant_id'].")>".$data['merchant_id']."</a>";
                },
                'format'=>'raw',
            ],
            'shop_url:url',
            'mobile',
            'email:email',
            [
                'attribute' => 'shop_data',
                'label' => 'Owner Name',
                'value' => function($data){
                    
                    $shop_data=json_decode($data['shop_data'],true);

                    return isset($shop_data['shop_owner'])?$shop_data['shop_owner']:'N/A';
                },
                'format'=>'raw',
                
            ],
            [
                'attribute' => 'marketplace',
                'label' => 'Marketplace',
                'value' => function($data){
                    return $data['marketplace'];
                },
                'format'=>'raw',
                'filter'=>array(
                                "jet"=>"jet",
                                "walmart"=>"Walmart",
                                "newegg"=>"Newegg",
                                "newegg_can"=>"Newegg canada",
                                "sears"=>"Sears",
                            ),
            ],
            
             'total_products',
             'total_orders',
             'total_revenue',
             'config_set',
           //  'purchased_status',
            // 'install_date',
            // 'uninstall_date',
            // 'last_purchased_plan',
            // 'shopify_plan_name',
            // 'shop_data:ntext',
            // 'marketplace_configuration:ntext',
            // 'token',
            // 'expiry_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
<div id="shop_data" style="display: none;"></div>
<script type="text/javascript">
    function getMerchant(id){
        var url='<?=$merchant_view;?>';
        $('#LoadingMSG').show();
        $.ajax({
            url:url,
            data:{merchant_id:id },
            method:'post',
            dataType:'json'
        }).done(function(msg) {
            //console.log(msg);
            $('#LoadingMSG').hide();
            $('#shop_data').html(msg.html);
            $('#shop_data').css("display","block");
            $('#shop_data #myModal').modal('show');
        });
    }
</script>
