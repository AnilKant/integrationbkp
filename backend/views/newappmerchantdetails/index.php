<?php

use backend\models\JetShopDetailsSearch;
use frontend\modules\jet\components\Dashboard\Earninginfo;
use frontend\modules\jet\components\Dashboard\OrderInfo;
use frontend\modules\jet\components\Dashboard\Productinfo;
use frontend\modules\jet\components\Jetappdetails;
use frontend\modules\jet\components\Data;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
// use common\models\User;
use common\models\Merchant as Merchantclass;



/* @var $this yii\web\View */
/* @var $searchModel common\models\JetMerchantsHelpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Merchants Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-merchants-help-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 5000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,


    	
        'columns' => [
            'merchant_id',
            'shop_name',
            'app_name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{disable}{cancel_recurring}',
                'buttons' => [
                    'disable' => function ($url,$model) {
                        if($model->user_status == Merchantclass::STATUS_ACTIVE) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-remove"> </span>',  
                                ['/merchant/disable','id'=>$model->merchant_id],
                                ['title' => 'Disable User']
                            );
                        }
                        elseif($model->user_status == Merchantclass::STATUS_DELETED) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-ok"> </span>',  
                                ['/merchant/enable','id'=>$model->merchant_id],
                                ['title' => 'Enable User']
                            );
                        }
                    },
                    'cancel_recurring' => function($url,$model) {
                        $action = Html::a(
                            'Cancel Recurring',  
                            ['/recurring-payment/choose-app','mid'=>$model->merchant_id],
                            ['title' => 'Cancel Recurring']
                        );
                        return $action; 
                    }
                ],
                  
            ],
        ],
    ]); 
    ?>

</div>
<?php if(isset($this->assetBundles)):?>
    <?php unset($this->assetBundles['yii\web\JqueryAsset']);?>
<?php endif; ?>  
<?php Pjax::end(); ?>