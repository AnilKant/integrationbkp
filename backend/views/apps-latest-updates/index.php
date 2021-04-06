<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppsFaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apps Latest Updates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apps-faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('New Updates', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'marketplace',
            'title',
            'is_enabled',
            [
                'attribute'=> 'expired_in',
                'format' => 'raw',
                'value'=> function($data)
                {
                    $interval = date_diff(date_create($data->expired_in),date_create(date('Y-m-d')));
                    $expired_days = [
                        0 => 'Today',
                        1 => 'Tomorrow',
                        7 => '1 week',
                        30 => '1 month',
                    ];
                    if(array_key_exists($interval->d,$expired_days))
                        return $expired_days[$interval->d];
                },
            ],
            [
                'attribute'=> 'description',
                'label'=>'Update details',
                'headerOptions' => ['width' => '250'],
                'format' => 'html',
                'value'=> function($data){
                    return Html::tag('div',$data['description'],['class'=>'panel-body']);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
