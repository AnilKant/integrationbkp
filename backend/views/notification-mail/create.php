<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\NotificationMail */

$this->title = 'Create Notification Mail';
$this->params['breadcrumbs'][] = ['label' => 'Notification Mails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-mail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
