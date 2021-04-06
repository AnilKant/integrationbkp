<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\pricefalls\models\pricefallsRegistration */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pricefalls Client Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricefalls-registration-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'fname',
            'lname',
            'shipping_source',
            'other_shipping_source',
            'mobile',
            'email:email',
            'reference:ntext',
            'agreement',
            'other_reference',
            'country',
        ],
    ]) ?>

</div>