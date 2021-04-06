<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WalmartClient */

$this->title = 'Create Walmart Client';
$this->params['breadcrumbs'][] = ['label' => 'Walmart Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="walmart-client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
