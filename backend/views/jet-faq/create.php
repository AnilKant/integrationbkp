<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JetFaq */

$this->title = 'Create Jet Faq';
$this->params['breadcrumbs'][] = ['label' => 'Jet Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
