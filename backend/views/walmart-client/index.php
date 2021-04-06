<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Walmart Client Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmart-client-details-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <a class="btn btn-primary pull-right" href ="export">Export New Client detail</a>
    <div class="clearfix"></div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            'merchant_id',
            [
                'attribute' => 'fname',
                'label' => 'First Name',
                'format' => 'html',
                //'value'=>'jet_product.price',
                'value' => function ($data) {
                    $viewUrl = Url::toRoute(['/walmart-client/view?id='.$data['merchant_id']]);
                    return '<a href="'.$viewUrl.'">'.$data['fname'].'</a>';
                }
            ],
            'lname',
            'mobile',
            'email',
            [
                'attribute' => 'consumer_id',
                'value' => 'configuration.consumer_id'
            ],
            //'configuration.consumer_id',
            //'configuration.secret_key',
            [
                'attribute' => 'secret_key',
                'value' => 'configuration.secret_key'
            ],
            'reference:ntext',            
            'other_reference',
            /*[
               'class' => 'yii\grid\ActionColumn',
               'template' => '{delete}',
           ],*/

        ],
    ]); ?>

</div>
