<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\NotificationMail */

$this->title = 'Update Notification Mail: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notification Mails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notification-mail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
