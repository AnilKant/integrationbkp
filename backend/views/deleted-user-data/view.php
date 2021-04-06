<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DeletedUserData */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Deleted User Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deleted-user-data-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'token',
            'installed_on',
            'shop_name',
            'email:email',
            'created_at',
            'phone_number',
            'country',
        ],
    ]) ?>

</div>
