<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AppsFaq */

$this->title = 'Latest Updates';
$this->params['breadcrumbs'][] = ['label' => 'Latest Updates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="apps-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
