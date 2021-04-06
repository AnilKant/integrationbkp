<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JetExtensionDetail */

$this->title = 'Email ID : '.$model->email;

$this->params['breadcrumbs'][] = ['label' => 'Jet-Integration', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-extension-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_id',
            'date',
            'expire_date',
            'email:email',
            'shopurl:ntext',
            'merchant_id',
            'status',
        	'customer_id',
        ],
    ]) ?>

</div>
