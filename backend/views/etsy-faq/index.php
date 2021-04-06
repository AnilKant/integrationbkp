<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JetFaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Etsy Faqs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Etsy-faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

     <div class="pull-right btn-right-set">
        <?= Html::a('Create Etsy Faq', ['create'], ['class' => 'btn btn-primary']) ?>
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
                            '<span class="glyphicon glyphicon-pencil jet-data"> </span>',  ['/etsy-faq/update','id'=>$model->id]

                        );
                    },
                    'view' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open jet-data"> </span>',  ['/etsy-faq/view','id'=>$model->id]

                        );
                    },
                    'delete' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"> </span>',['/etsy-faq/delete','id'=>$model->id]
                        );
                    },

                ],
            ],
        ],
    ]); ?>
</div>
