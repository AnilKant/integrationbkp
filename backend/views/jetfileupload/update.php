<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\JetProductFileUpload */

$this->title = 'Update Jet Product File Upload: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jet Product File Uploads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jet-product-file-upload-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
