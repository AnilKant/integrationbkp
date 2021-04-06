<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoFruugoInfo */

$this->title = 'Update Magento Fruugo Info: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Magento Fruugo Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="magento-fruugo-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
