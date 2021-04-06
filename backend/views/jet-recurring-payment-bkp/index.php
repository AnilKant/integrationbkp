<?php


use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\JetExtensionDetail;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\JetRecurringPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments Details';
$this->params['breadcrumbs'][] = $this->title;

$urlpayment= \yii\helpers\Url::toRoute(['jet-recurring-payment/viewpayment']);
$url='';
?>
<div class="jet-recurring-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
   
<?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_id',
        		
        	[
        		'label'=>'Shop Name',
        		'value' => function($data){	  
                    return $data->merchant['shop_name'];
        		},
	        	'format'=>'raw',
        	],
        	[
	        	'label'=>'Expire On',
	        	'value' => function($data){	        	
	        	  return $data->merchant['expired_on'];
	        	},
	        	'format'=>'raw',
        	],
            'billing_on',
            'activated_on',
        	'plan_type',
            'status',
            [
                'label'=>'Is Installed',
                'value' => function($data){             
                  return $data->merchant['install_status'];
                },
                'format'=>'raw',
            ],
        	
            [
            		'class' => 'yii\grid\ActionColumn',
            		'template' => '{view}{link}',
            		'buttons' => [
            		'view' => function ($url,$model)
            				{
            					return Html::a(
	            								'<span class="glyphicon glyphicon-eye-open "> </span>',
	            								'javascript:void(0)',['data-pjax'=>0,'onclick'=>'clickView(this.id,this.rev,this.type)','title'=>'Check Payment Detail','id'=>$model->id,'rev'=>$model->merchant_id,'type'=>$model->plan_type]
            								);
            					
            			  },
            		],
    		], 
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
<div id="view_payment" style="display:none">
</div>
<script type="text/javascript">

	function clickView(id,mid,type){
		j$('#LoadingMSG').show();
		$.post("<?= $urlpayment ?>",
	        {
				id:id, 
				merchant_id : mid,
				type : type,
	        },
	        function(data,status){
	        	j$('#LoadingMSG').hide();
	            j$('#view_payment').html(data);
	            j$('#view_payment').css("display","block");	  
	            $('#view_payment #myModal').modal('show');
	        });
	}

</script>
