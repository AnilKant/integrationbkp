<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\JetShopDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jet Shop Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jet-shop-details-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'shop_url:url',
            'shop_name',
            'email:email',
            'country_code',
            'currency',
            'seller_username',
            'seller_password',
            'prod_import_limit',
            'install_status',
            'installed_on',
            'expired_on',
            'purchased_on',
            'purchase_status',
        ],
    ]) ?>

</div>
