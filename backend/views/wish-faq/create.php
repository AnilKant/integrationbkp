<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WalmartFaq */

$this->title = 'Create Wish Faq';
$this->params['breadcrumbs'][] = ['label' => 'Wish Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newegg-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
