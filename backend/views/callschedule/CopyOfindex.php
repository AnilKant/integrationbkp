<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CallscheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Callschedules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callschedule-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?/*= Html::a('Create Callschedule', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'merchant_id',
            'number',
            'shop_url:url',
            'marketplace',
             'status',
             'time',
             'no_of_request',
             'time_zone',
             'preferred_timeslot',
             'response',

/*            ['class' => 'yii\grid\ActionColumn'],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action','headerOptions' => ['width' => '80'],
                'template' => '{update}{view}',
            ],
        ],
    ]); ?>
</div>
