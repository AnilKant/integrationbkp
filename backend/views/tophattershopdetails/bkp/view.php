<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\Tophattershopdetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tophattershopdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tophattershopdetails-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'token',
            'install_status',
            'install_date',
            'uninstall_dates',
            'purchase_status',
            'expire_date',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
