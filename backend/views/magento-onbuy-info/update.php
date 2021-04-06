<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoOnbuyInfo */

$this->title = 'Update Magento Onbuy Info: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Magento Onbuy Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="magento-Onbuy-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
