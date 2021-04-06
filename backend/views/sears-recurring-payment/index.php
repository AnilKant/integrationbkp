<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\SearsRecurringPayment;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SearsRecurringPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sears Payments Details';
$this->params['breadcrumbs'][] = $this->title;
$urlpayment= \yii\helpers\Url::toRoute(['sears-recurring-payment/viewpayment']);
$urlcancel= \yii\helpers\Url::toRoute(['sears-recurring-payment/cancelpayment']);

$url='';
?>
<div class="sears-recurring-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_id',
            'billing_on',
            'activated_on',
            //'plan_type',
            //'status',
            [
            'attribute'=>'plan_type',
            'label'=>'Plan Type',
            'value'=>'plan_type',
            'filter'=> ArrayHelper::map(SearsRecurringPayment::find()->asArray()->all(), 'plan_type', 'plan_type'),
            ],
            [
            'attribute'=>'status',
            'label'=>'Status',
            'value'=>'status',
            'filter'=> ArrayHelper::map(SearsRecurringPayment::find()->asArray()->all(), 'status', 'status'),
            ],
            //'recurring_data:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{link}{cancel}',
                'buttons' => [
                'view' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open "> </span>',
                            'javascript:void(0)',['data-pjax'=>0,'onclick'=>'clickView(this.id,this.rev,this.type)','title'=>'View Payment Detail','id'=>$model->id,'rev'=>$model->merchant_id,'type'=>$model->payment_type]
                        );
                    },
                'cancel' => function ($url,$model)
                    {
                        return Html::a(
                            '<span class="glyphicon glyphicon-remove-circle"> </span>',
                            'javascript:void(0)',['data-pjax'=>0,'onclick'=>'cancelmonthly(this.id,this.rev,this.type)','title'=>'Cancel Payment Detail','id'=>$model->id,'rev'=>$model->merchant_id,'type'=>$model->payment_type]
                        );
                    },
                ],
            ], 
        ],
    ]); ?>

</div>
<div id="view_payment" style="display:none">
<script type="text/javascript">
    function cancelmonthly(id,mid,type){
        if (!confirm("Are you sure ?"))
        {
            return false;
        }
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
    $(document).ready(function(){
        $(".glyphicon-trash").hide();
        $(".glyphicon-pencil").hide();
    });
</script>