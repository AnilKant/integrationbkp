<?php 
	
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\JetOrderDetail;

$this->title = 'Merchants-with Orders  Completed';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
	Yii::$app->MyDashboard->header();
?>

<div class="jet-extension-detail-index">

    <h2><?= Html::encode($this->title) ?></h2>
   
	<?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
	<?php 
			
			    echo GridView::widget([
			    	'id'=>"jet_extention_details",
			        'dataProvider' => $model,
			        'columns' => [
			        		'merchant_id',
			        		[
			        				'label'=>'Shop URL',
			        				'attribute'=>'shopurl',
			        		],
			        		'email',
			        		[
				        		'label'=>'Order',
				        		'value' => function($data){
					        		$result=JetOrderDetail::find()->select('id')->where(['status'=>'complete','merchant_id'=>$data->merchant_id])->all();
					        		return count($result);
				        		},
				        		'format'=>'raw',
			        		],
			        		'status',
			        		'app_status',
			        		'expire_date',
			            
			            [
			                'class' => 'yii\grid\ActionColumn',
			                'template' => '{view} {link}',
			                'buttons' => [
			                    'link' => function ($url,$model,$key) {
			                        return '<a data-pjax="0" href="'.Yii::$app->urlManagerFrontEnd->createUrl(['site/managerlogin','ext'=>$model['merchant_id']]).'">Login as</a>'; 
					    	},
						
			                ],
			            ],
			        ],
			    ]); 
   	 /* 	}
	} */
	?>
<?php Pjax::end(); ?>
</div>
