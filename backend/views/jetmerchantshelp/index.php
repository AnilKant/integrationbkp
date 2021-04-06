<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\JetMerchantsHelpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jet Merchants Helps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-merchants-help-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Jet Merchants Help', ['create'], ['class' => 'btn btn-success'])  */?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    	'rowOptions'=>function ($model){
    		if ($model->status=='active'){
    			return ['class'=>'danger'];
    		}elseif ($model->status=='resolved'){
    			return ['class'=>'success'];
    		}
    	},
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_name',
            'merchant_store_name',
            'merchant_email_id:email',
            'subject',
        	[
        		'format'=>'raw',
        		'attribute' => 'status',
        		'contentOptions' =>function ($model, $key, $index, $column){
        		return ['class' => 'validate'];
        		},
        		'value'=>'status',
        		
        		'filter'=>array("active"=>"active","resolved"=>"resolved"),
        	],
            // 'query:ntext',
            // 'solution:ntext',
            // 'status',
             'time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
