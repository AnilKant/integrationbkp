<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SearsExtensionDetail */

$this->title = 'Create Sears Extension Detail';
$this->params['breadcrumbs'][] = ['label' => 'Sears Extension Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sears-extension-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
