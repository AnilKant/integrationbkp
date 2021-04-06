<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WalmartFaq */

$this->title = 'Create Walmart Faq';
$this->params['breadcrumbs'][] = ['label' => 'Walmart Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmart-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
