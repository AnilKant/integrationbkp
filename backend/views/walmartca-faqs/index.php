<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WalmartcaFaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Walmartca Faqs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Walmartca-faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

     <div class="pull-right btn-right-set">
        <?= Html::a('Create Walmartca Faq', ['create'], ['class' => 'btn btn-primary']) ?>
    </div class="btn-right-set">
    <div class="clearfix"></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'query:ntext',
            'description:ntext',

            //['class' => 'yii\grid\ActionColumn'],
             [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {view}',
                'buttons' => [
                    'update' => function ($url,$model,$key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil jet-data"> </span>',  ['/walmartca-faqs/update','id'=>$model->id]

                        );
                    },
                    'view' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open jet-data"> </span>',  ['/walmartca-faqs/view','id'=>$model->id]

                        );
                    },
                    'delete' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"> </span>',['/walmartca-faqs/delete','id'=>$model->id]
                        );
                    },

                ],
            ],
        ],
    ]); ?>
</div>
