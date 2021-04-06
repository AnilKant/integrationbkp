<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\jet\models\JetRegistration */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Jet Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-registration-view">

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
            'name',
            'shipping_source',
            'other_shipping_source',
            'mobile',
            'email:email',
            'reference:ntext',
            'already_selling',
            'previous_api_provider_name',
            'is_uninstalled_previous',
            'agreement',
            'other_reference',
        ],
    ]) ?>

</div>
