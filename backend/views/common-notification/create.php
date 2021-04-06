<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CommonNotification */

$this->title = 'Create Common Notification';
$this->params['breadcrumbs'][] = ['label' => 'Common Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="common-notification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
