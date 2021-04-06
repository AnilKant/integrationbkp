<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SearsFaq */

$this->title = 'Create Sears Faq';
$this->params['breadcrumbs'][] = ['label' => 'Sears Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Sears-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
