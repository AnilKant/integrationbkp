<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JetFaq */

$this->title = 'Create Jet Faq';
$this->params['breadcrumbs'][] = ['label' => 'Etsy Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="etsy-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
