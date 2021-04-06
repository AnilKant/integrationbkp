<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AppsFaq */

$this->title = 'Update Apps Faq: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Apps Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="apps-faq-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
