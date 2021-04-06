<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\PricingPlan;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PricingPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pricing Plans');
$this->params['breadcrumbs'][] = $this->title;

$urlcondition = \yii\helpers\Url::toRoute(['pricingplan/viewcondition']);

?>
<div class="pricing-plan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        
        <?= Html::a(Yii::t('app', 'Create Pricing Plan'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('View Condition', ['/conditional-charge/index'], ['class'=>'btn btn-primary']) ?>
         <?= Html::a('Create Condition', ['/conditional-charge/create'], ['class'=>'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'plan_name',
            [
            'attribute'=> 'plan_type',
            'value' => 'plan_type',
            'filter'=> ArrayHelper::map(PricingPlan::find()->asArray()->all(), 'plan_type', 'plan_type'),
            ],

            'duration',
             [
            'attribute'=> 'plan_status',
            'value' => 'plan_status',
            'filter'=> ArrayHelper::map(PricingPlan::find()->asArray()->all(), 'plan_status', 'plan_status'),
            ],
            [
              'attribute' => 'trial_period._',
              'value'=>'$data->trial_period . " " . Days',
              'value' => 'trial_period',
            ],
            'base_price',
            'special_price',
            'capped_amount',
            [
            'attribute'=> 'apply_on',
            'value' => 'apply_on',
            'filter'=> ArrayHelper::map(PricingPlan::find()->asArray()->all(), 'apply_on', 'apply_on'),
            ],
            [
                        'attribute'=>'additional_condition',
                        //'contentOptions'=>['style'=>'width: 400px;'],
                        'format'=>'raw',
                        'value' => function($data){
                           $additional_condition = explode(",",$data['additional_condition']);
                           $count = count($additional_condition);
                           return "<a onclick='condition($data[id])' >".$count." conditions applied</a>";
                        },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<div id="conditions_view"></div>
<script type="text/javascript">
   
    function condition($id)
    {
        var url='<?= $urlcondition; ?>';
        var id = $id;
        $('#LoadingMSG').show();
        $.ajax({
            method: "post",
            url: url,
            data: {id:id}
        })
            .done(function(msg){
                $('#LoadingMSG').hide();
                $('#conditions_view').html(msg);
                $('#conditions_view').css("display","block");
                $('#conditions_view #myModal').modal();
                
            });
    }
</script>