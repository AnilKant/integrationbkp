<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JetFaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jet Faqs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

     <div class="pull-right btn-right-set">
        <?= Html::a('Create Jet Faq', ['create'], ['class' => 'btn btn-primary']) ?>
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
