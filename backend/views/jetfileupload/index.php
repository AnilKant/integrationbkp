<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\JetProductFileUploadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jet Product File Uploads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-product-file-upload-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options'=>['class'=>'table-responsive grid-view'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'merchant_id',
            'local_file_path',
            'file_name',
        	
            //'file_type',
            // 'file_url:ntext',
            'jet_file_id',
            // 'received',
            // 'processing_start',
            // 'processing_end',
            'total_processed',
            'error_count',
            // 'error_url:ntext',
            // 'error_excerpt:ntext',
            'expires_in_seconds',
            'file_upload_time',
            // 'error:ntext',
        	[
        		'attribute'=> 'file_type',
        		'label'=>'File Type',
        		'headerOptions' => ['width' => 'auto','data-toggle'=>'tooltip1','title'=>'Type of file uploaded'],
        		'filter' => ["MerchantSKUs"=>"MerchantSKUs","Price"=>"Price","Inventory"=>"Inventory","Variation"=>"Variation"],
        	],
            [
	        		'attribute'=> 'status',
	        		'label'=>'Status',
	        		'headerOptions' => ['width' => 'auto','data-toggle'=>'tooltip1','title'=>'File upload status'],
	        		'filter' => ["Acknowledged"=>"Acknowledged","Processing"=>"Processing","Processed with errors"=>"Processed with errors","Processed successfully"=>"Processed successfully"],
	        ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
