<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WalmartcaFaq */

$this->title = 'Update Walmartca Faq: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Walmartca Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="walmartca-faq-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
