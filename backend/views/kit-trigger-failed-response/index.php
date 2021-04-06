<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\KitTriggerFailedResponseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kit Trigger Failed Responses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-trigger-failed-response-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'app_name',
            'skill_name',
            'shop_name',
            'response:ntext',
            'created_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}{view}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
