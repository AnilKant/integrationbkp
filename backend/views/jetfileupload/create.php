<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\JetProductFileUpload */

$this->title = 'Create Jet Product File Upload';
$this->params['breadcrumbs'][] = ['label' => 'Jet Product File Uploads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-product-file-upload-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
