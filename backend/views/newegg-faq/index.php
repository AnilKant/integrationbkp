<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\neweggFaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Newegg Faqs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newegg-faq-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Newegg Faq', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'query:ntext',
            'description:ntext',
            'marketplace:text',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
