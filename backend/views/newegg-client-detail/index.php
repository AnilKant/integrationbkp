<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NeweggClientDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Newegg Client Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newegg-client-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?/*= Html::a('Create Newegg Client Detail', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_id',
            'name',
            'shipping_source',
            'other_shipping_source',
             'mobile',
             'email:email',
             'reference:ntext',
             'agreement',
             'other_reference',

            /*['class' => 'yii\grid\ActionColumn'],*/
        ],
    ]); ?>

</div>
