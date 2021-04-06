<?php
	
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
	
	/* @var $this yii\web\View */
	/* @var $searchModel backend\models\EtsyPaymentSearch */
	/* @var $dataProvider yii\data\ActiveDataProvider */
	
	$urlpayment= \yii\helpers\Url::toRoute(['etsy-payment/viewpayment']);
	$urlcancel= \yii\helpers\Url::toRoute(['etsy-payment/cancelpayment']);
	
	$this->title = 'Etsy Payments';
	$this->params['breadcrumbs'][] = $this->title;
	$planFilters = [
	        '1 Year Subscription' => '1 Year Subscription',
	        'Half-yearly Subscription' => 'Half-yearly Subscription',
	        'Recurring Plan (Monthly)' => 'Recurring Plan (Monthly)',
	        'custom' => 'Custom charges',
    ];
?>
<div class="etsy-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?php Pjax::begin(['id'=>'pjax-new-gridview', 'timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'rowOptions'=>function ($model){
			if ($model->status=='cancelled'){
				return ['class'=>'danger'];
			}elseif ($model->status=='active'){
				return ['class'=>'success'];
			}
		},
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			
			'id',
			'merchant_id',
			'billing_on',
			'activated_on',
			[
				'format'=>'raw',
				'attribute' => 'plan_type',
				'contentOptions' =>function ($model, $key, $index, $column){
					return ['class' => 'validate'];
				},
				'value'=>'plan_type',
				
				'filter'=>$planFilters,
			],
			[
				'format'=>'raw',
				'attribute' => 'status',
				'contentOptions' =>function ($model, $key, $index, $column){
					return ['class' => 'validate'];
				},
				'value'=>'status',
				
				'filter'=>array("active"=>"active","cancelled"=>"cancelled"),
			],
			// 'recurring_data:ntext',
			
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{cancel}{link}',
				'buttons' => [
					'view' => function ($url,$model)
					{
						return Html::a(
							'<span class="glyphicon glyphicon-eye-open "> </span>',
							'javascript:void(0)',['data-pjax'=>0,'onclick'=>'clickView(this.id,this.rev,this.type)','title'=>'Check Payment Detail','id'=>$model->id,'rev'=>$model->merchant_id,'type'=>$model->payment_type]
						);
					},
					'cancel' => function ($url,$model)
					{
						if ($model->payment_type=='recurring' && $model->status=='active'){
							return Html::a(
								'<span class="glyphicon glyphicon-remove-circle"> </span>',
								'javascript:void(0)',['data-pjax'=>0,'onclick'=>'cancelmonthly(this.id,this.type,this)','title'=>'Check Payment Detail','id'=>$model->id,'data-m_id'=>$model->merchant_id,'type'=>$model->payment_type]
							);
						}
						
					},
				],
			],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>

<div id="view_payment" style="display:none"></div>
<script>
    function cancelmonthly(id,type,ele){
        if (!confirm("Are you sure to cancel recurring payment?"))
        {
            return false;
        }
        console.log(ele);
        var mid = $(ele).data("m_id")
        
        $('#LoadingMSG').show();
        $.post("<?= $urlcancel ?>",
            {
                id:id,
                merchant_id : mid,
                type : type,
            },
            function(data,status){

                $('#LoadingMSG').hide();
                alert(data);

            });
    }


    function clickView(id,mid,type){
        $('#LoadingMSG').show();
        $.post("<?= $urlpayment ?>",
            {
                id:id,
                merchant_id : mid,
                type : type,
            },
            function(data,status){
                $('#LoadingMSG').hide();
                $('#view_payment').html(data);
                $('#view_payment').css("display","block");
                $('#view_payment #myModal').modal('show');
            });
    }
</script>