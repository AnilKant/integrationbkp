<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\jet\models\JetRegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jet Client Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-registration-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'merchant_id',
            'name',
            'mobile',
            'email:email',
            [
                'label'=>'Jet M-ID',
                'attribute'=>'merchant',
                'value' => function($data){             
                  return $data->jet_configuration['merchant'];
                },
            ],
            [
                'label'=>'Install Date',
                'attribute'=>'installed_on',
                'value' => function($data){             
                  return $data->jet_shop_details['installed_on'];
                },
            ],
            [
                'label'=>'Un-Install Date',
                'attribute'=>'expired_on',
                'value' => function($data){             
                  return $data->jet_shop_details['expired_on'];
                },
            ],
            'already_selling',
            'is_uninstalled_previous',
            'previous_api_provider_name',
            'shipping_source',
            'other_shipping_source',
            'time_zone',
            'time_slot',
            // 'agreement',
            'reference:ntext',            
            'other_reference',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
