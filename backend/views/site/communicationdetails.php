<?php 
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
$this->title = "Merchants Details";
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
/*   echo "<pre>";
print_r($model);
die;  */
 ?>

<div class="jet-extension-detail-index" style="margin-top: 2%;">


    <h2><?= Html::encode($this->title) ?></h2>
   
	<?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
	
	

	<?php 

			   echo GridView::widget([
			    	'id'=>"jet_details",
			        'dataProvider' => $model,
			        'columns' => [
			        		/* [
			        				'label'=>'M-ID',
			        				'attribute'=>'merchant_id',
			        		], */
			                 //'merchant_id',
			                 
			                 [
			                     'label'=>'Id',
			                     'value' => function($data){
			                         
			                         return $data->merchant_id;
			                     },
			                 ],   
			                 [
    			                 'label'=>'Shop',
    			                 'value' => function($data){
    			                     return $data["merchantid"]['username'];
    			                 },
			                 ],
			                 'merchant_email',
			                 'merchant',
			                 [
    			                 'label'=>'Skype Id',
    			                 'value' => function($data){
    			                 return $data["api"]['skype_id'];
    			                 },
			                 ],
			                 //'username',
			                 
			                 [
			                 'class' => 'yii\grid\ActionColumn',
			                 'template' => '{link}',
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