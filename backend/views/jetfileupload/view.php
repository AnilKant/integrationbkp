<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\JetProductFileUpload */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jet Product File Uploads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-product-file-upload-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'local_file_path',
            'file_name',
            'file_type',
            'file_url:ntext',
            'jet_file_id',
            'received',
            'processing_start',
            'processing_end',
            'total_processed',
            'error_count',
            'error_url:ntext',
            'error_excerpt:ntext',
            'expires_in_seconds',
            'file_upload_time',
            'error:ntext',
            'status',
        ],
    ]) ?>

</div>
