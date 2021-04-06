<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LatestUpdates */
$name = $model->fname.' '.$model->lname;
$this->title = 'Edit : "'.$name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Walmart Client Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $name, 'url' => ['view', 'id' => $model->merchant_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="latest-updates-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
