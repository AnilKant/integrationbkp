<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Client Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-test-api-index">


 <a class="'btn btn-primary" href ="export" style="padding: 10px;">Export New Client detail</a>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            'merchant_id',
            'registration.name',
            
            'registration.mobile',
            'registration.email',
            'user',
            'secret',
            'merchant',
            'fulfillment_node',
            'registration.shipping_source',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>

</div>
