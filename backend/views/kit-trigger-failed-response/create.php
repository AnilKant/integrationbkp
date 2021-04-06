<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KitTriggerFailedResponse */

$this->title = 'Create Kit Trigger Failed Response';
$this->params['breadcrumbs'][] = ['label' => 'Kit Trigger Failed Responses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-trigger-failed-response-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
