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
            
            'no_of_request',
        	[
        		'attribute'=>'time',
        		'label'=>'Call schedule Time',
        		'headerOptions' => ['width' => '400'],
        		
        	],
        		
        	[
        				'attribute'=>'preferred_date',
        				'label'=>'Preffered Call Time',
        				'headerOptions' => ['width' => '400'],
        				
        	],
            'preferred_timeslot',
            [
                'attribute'=>'time_zone',
                'label'=>'Time Zone',
                'headerOptions' => ['width' => '400'],
                'filter' => ["IST"=>"IST","EST"=>"EST","GMT"=>"GMT","UTC"=>"UTC"],
            ],
             
            [
                'attribute'=>'status',
                'label'=>'Call Status',
                'headerOptions' => ['width' => '400'],
                'filter' => ["pending"=>"pending","inprogress"=>"inprogress","completed"=>"completed"],
            ],
            [
                'attribute'=>'marketplace',
                'label'=>'Marketplace',
                'headerOptions' => ['width' => '400'],
                'filter' => ["jet"=>"jet","walmart"=>"walmart"],
            ],
            'response',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action','headerOptions' => ['width' => '80'],
                'template' => '{update}{view}',
            ],
        ],
    ]); ?>
</div>
