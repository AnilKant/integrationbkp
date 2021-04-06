<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WalmartFaq */

$this->title = 'Update Wish Faq: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wish Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="newegg-faq-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
