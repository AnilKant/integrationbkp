<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MagentoOnbuyInfo */

$this->title = 'Create Magento Onbuy Info';
$this->params['breadcrumbs'][] = ['label' => 'Magento Onbuy Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magento-Onbuy-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
