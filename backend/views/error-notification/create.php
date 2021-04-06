<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ErrorNotification */

$this->title = 'Create Error Notification';
$this->params['breadcrumbs'][] = ['label' => 'Error Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-notification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
