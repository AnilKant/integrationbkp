<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ReverbShopDetails */

$this->title = 'Create Reverb Shop Details';
$this->params['breadcrumbs'][] = ['label' => 'Reverb Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reverb-shop-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
