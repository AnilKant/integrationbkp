<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppliedCoupanCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Applied Coupan Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applied-coupan-code-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Applied Coupan Code', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_id',
            'used_on',
            'coupan_code',
            'activated_date',
            // 'coupan_code_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
