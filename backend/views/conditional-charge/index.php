<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ConditionalChargeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Conditional Charges');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conditional-charge-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Conditional Charge'), ['create'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('View Pricing Plan', ['/pricingplan/index'], ['class'=>'btn btn-primary']) ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'charge_name',
            'charge_description',
            'charge_condition',
            'charge_range',
            'merchant_base',
            // 'charge_type',
            // 'apply',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
