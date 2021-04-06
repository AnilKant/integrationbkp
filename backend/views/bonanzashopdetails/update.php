<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\fruugo\models\bonanzashopdetails */

$this->title = 'Update Bonanzashopdetails: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'bonanzashopdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bonanzashopdetails-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
