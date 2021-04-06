<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\bonanza\models\bonanzaRegistration */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bonanza Client Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonanza-registration-view">

    <h1><?= Html::encode($this->title) ?></h1>

   
  <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'fname',
            'lname',
            'mobile',
            'email:email',
            'reference:ntext',
            'agreement',
            'other_reference',
        ],
    ]) ?>

</div>