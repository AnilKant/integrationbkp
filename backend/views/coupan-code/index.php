<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\CoupanCode;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CoupanCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coupan Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupan-code-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coupan Code', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'promo_code',
            'status',
            [
            'attribute'=> 'status',
            'value' => 'status',
            'filter'=> ArrayHelper::map(CoupanCode::find()->asArray()->all(), 'status', 'status'),
            ],
            'amount_type',
            [
            'attribute'=> 'amount_type',
            'value' => 'amount_type',
            'filter'=> ArrayHelper::map(CoupanCode::find()->asArray()->all(), 'amount_type', 'amount_type'),
            ],
            'amount',
            'applied_on',
            [
            'attribute'=> 'applied_on',
            'value' => 'applied_on',
            'filter'=> ArrayHelper::map(CoupanCode::find()->asArray()->all(), 'applied_on', 'applied_on'),
            ],
            'expire_date',
            'applied_merchant',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
