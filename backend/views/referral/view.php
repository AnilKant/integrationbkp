<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ReferralUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Referral Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referral-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php /*echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])*/ ?>
        <?php /*echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'referrer_id',
            'merchant_id',
            'app',
            'status',
            'installation_date',
            'payment_date',
        ],
    ]) ?>

</div>
