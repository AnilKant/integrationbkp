<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CatchShopDetails */

$this->title = 'Create Catch Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Catch Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catch-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
