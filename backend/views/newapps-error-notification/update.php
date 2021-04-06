<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\NewappsErrorNotification */

$this->title = 'Update Error Notification: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Error Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="error-notification-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
