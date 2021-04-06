<?php 
	
use backend\components\MyDashboard;
use yii\grid\GridView;
use common\models\JetActiveMerchants;
use yii\helpers\Html;
use yii\widgets\Pjax;
$this->title = "Merchants-Active (Today)";
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
	Yii::$app->MyDashboard->header();
?>
<div class="jet-extension-detail-index" style="margin-top: 2%;">

<?php 
	Yii::$app->MyDashboard->activeMerchantsMenu();
?>
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
			        				'attribute'=>'shop_url',
			        		],
			        		'email',
			        		'purchase_status',
			        		[
				        		'label'=>'Visiting Time',
				        		'value' => function($data){
				        		$sql = 'SELECT updated_at FROM jet_active_merchants where merchant_id='.$data->merchant_id;
				        		$result = JetActiveMerchants::findBySql($sql)->one();
				        		return $result->updated_at;
			        		},
			        		'format'=>'raw',
			        		],
			        		'install_status',
			        		'expired_on',
			             
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
	?>
<?php Pjax::end(); ?>
</div>