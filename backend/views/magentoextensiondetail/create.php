<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MagentoExtensionDetail */

$this->title = 'Create Magento Extension Detail';
$this->params['breadcrumbs'][] = ['label' => 'Magento Extension Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magento-extension-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
