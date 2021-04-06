<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\KitTriggerFailedResponse */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kit Trigger Failed Responses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-trigger-failed-response-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'shop_name',
            'skill_name',
            'response:ntext',
            'updated_at',
            'created_at',
            'app_name',
        ],
    ]) ?>

</div>
