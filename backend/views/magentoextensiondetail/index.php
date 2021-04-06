<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MagentoExtensionDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Magento Extension Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magento-extension-detail-index">

   <h1><?= Html::encode($this->title) ?></h1>

   <div class="pull-right">
        <?= Html::a('Create New Detail', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Sync Details', ['syncinfo'], ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="clearfix"></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'store_url:url',
            'email:email',
            'total_product',
            'published',
            'unpublished',
            'total_order',
            'complete_orders',
            [
                'attribute'=> 'config_set',
                'label'=>'Config Set',
                'filter' => ["yes"=>"yes","no"=>"no"],
                'filterInputOptions'=> ['class' => 'form-control']
            ],
            'totalRevenue',
            'updated_at',            
            [
                'attribute'=> 'pilot_seller',
                'label'=>'Pilot Seller',
                'filter' => ["yes"=>"yes","no"=>"no"],
                'filterInputOptions'=> ['class' => 'form-control']
            ],
            [
                'attribute'=> 'plateform',
                'label'=>'Plateform',
                'filter' => ["m1"=>"m1","m2"=>"m2",'woocommerce'=>'woocommerce'],
                'filterInputOptions'=> ['class' => 'form-control']
            ],
            'ac_details:ntext',
            'last_response:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
