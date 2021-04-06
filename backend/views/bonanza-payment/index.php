<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BonanzaPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$urlpayment= \yii\helpers\Url::toRoute(['bonanza-payment/viewpayment']);
$urlcancel= \yii\helpers\Url::toRoute(['bonanza-payment/cancelpayment']);

$this->title = 'Bonanza Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonanza-payment-index">

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

            //'id',
            'charge_id',
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

                'filter'=>array("Annual Subscription Plan"=>"Annual Subscription Plan","6 Months Subscription Plan"=>"6 Months Subscription Plan","Monthly Recurring Subscription"=>"Monthly Recurring Subscription","Annual Subscription Plan (10% off)"=>"Annual Subscription Plan (10% off)"),
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
                            'javascript:void(0)',['data-pjax'=>0,'onclick'=>'clickView(this.id,this.rev,this.type)','title'=>'Check Payment Detail','id'=>$model->charge_id,'rev'=>$model->merchant_id,'type'=>$model->plan_type]
                        );
                    },
                    'cancel' => function ($url,$model)
                    {
                        if ($model->plan_type=='Monthly Recurring Subscription' && $model->status=='active'){
                            return Html::a(
                                '<span class="glyphicon glyphicon-remove-circle"> </span>',
                                'javascript:void(0)',['data-pjax'=>0,'onclick'=>'cancelmonthly(this.id,this.m_id,this.type)','title'=>'Check Payment Detail','id'=>$model->charge_id,'m_id'=>$model->merchant_id,'type'=>$model->plan_type]
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
    function cancelmonthly(id,mid,type){
        if (!confirm("Are you sure to cancel recurring payment?"))
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
</script>