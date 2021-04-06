<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WalmartcaFaq */

$this->title = 'Create Walmartca Faq';
$this->params['breadcrumbs'][] = ['label' => 'Walmartca Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmartca-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
