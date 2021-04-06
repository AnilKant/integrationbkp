<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\sears\models\SearsRegistration */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sears Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sears-registration-view">
<h1><?= Html::encode($this->title) ?></h1>
        <div class="grid-view">
         <div class="btn-left-set">
    
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
         </div class="btn-left-set">

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'name',
            'mobile',
            'reference:ntext',
            'agreement',
            'other_reference',
            'selling_on_sears',
            'selling_on_sears_source',
        ],
    ]) ?>
    </div>

</div>
