<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SearsExtensionDetail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sears Extension Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sears-extension-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'install_date',
            'date',
            'expire_date',
            'status',
            'app_status',
            'uninstall_date',
        ],
    ]) ?>

</div>
