<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\NeweggClientDetail */

$this->title = 'Create Newegg Client Detail';
$this->params['breadcrumbs'][] = ['label' => 'Newegg Client Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newegg-client-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>