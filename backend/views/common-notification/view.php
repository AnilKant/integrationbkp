<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CommonNotification */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Common Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="common-notification-view">
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
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'heading',
            'html_content:ntext',
            'sort_order',
            'from_date',
            'to_date',
            'enable',
            'marketplace',
        ],
    ]) ?>
    </div>
</div>
