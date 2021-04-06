<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\WalmartRecurringPayment;


/* @var $this yii\web\View */
/* @var $searchModel app\models\WalmartRecurringPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Walmart-recurring-payment-index';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmart-recurring-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_id',
           /* [
                'attribute'=>'shop_name',
                'label'=>'Shop Name',
                'value'=>'walmartShopDetails.shop_name',
            ],*/
            'billing_on',
            'activated_on',
            //'walmartExtensionDetail.expire_date',
            [
                'attribute'=>'expire_date',
                'format'=>'raw',
                'label'=>'Expire Date',
                'value'=>'walmartExtensionDetail.expire_date',
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
                'attribute'=>'plan_type',
                'label'=>'Plan Type',
                'value'=>'plan_type',
                
                'filter'=> ArrayHelper::map(walmartRecurringPayment::find()->asArray()->all(), 'plan_type', 'plan_type'),
              
            
            ],
            // 'status',
            // 'recurring_data:ntext',

        ],
    ]); ?>

</div>
