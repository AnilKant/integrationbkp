<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\fruugoRegistration */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fruugo Client Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fruugo-registration-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="grid-view">
         
    
   

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'fname',
            'lname',
            'store_name',
            'shipping_source',
            'other_shipping_source',
            'mobile',
            'email:email',
            'annual_revenue',
            'reference:ntext',
            'agreement',
            'other_reference',
            'country',
            'selling_on_fruugo',
            'selling_on_fruugo_source',
            'other_selling_source',
            'approved_by_fruugo',
        ],
    ]) ?>
     </div>

</div>
