<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JetFaq */

$this->title = 'Update Jet Faq: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jet Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jet-faq-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
