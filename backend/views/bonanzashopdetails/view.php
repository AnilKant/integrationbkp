<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\bonanza\models\bonanzashopdetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'bonanzashopdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonanzashopdetails-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'merchant_id',
            'install_status',
            'installed_on',
            'seller_username',
            'seller_password',
            'uninstall_date',
            'purchase_status',
            'expired_on',
            'created_at',
            'disable_cron',
        ],
    ]) ?>

</div>
