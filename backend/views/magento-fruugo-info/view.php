<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\components\Data;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoFruugoInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Magento Fruugo Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$query1 = "SELECT `fruugo_order_id`,`order_total`,`order_date` FROM `magento_fruugo_orders` WHERE `merchant_info_id`=".$model->id;
$totalOrders = Data::sqlRecords($query1,"all",'select');
?>
<div class="magento-fruugo-info-view">

    <h1><?= Html::encode($this->title) ?></h1>

    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'domain',
            'email:email',
            'live_sku',
            'uploaded_sku',
            'install_on',
            'framework',
        ],
    ]) ?>

</div>

<h1>Total Merchant orders</h1>

<table class="table table-striped table-bordered">
    <tr>
        <th>Fruugo Order Id</th>
        <th>Order Total Price</th>
        <th>Order Date</th>
    </tr>
    <?php 
        if(is_array($totalOrders)){
        foreach ($totalOrders as $key => $value) {
          
    ?>
    <tr>
        <td><?= $value['fruugo_order_id']; ?></td>
        <td><?= $value['order_total'];?></td>
        <td><?= $value['order_date'];?></td>
    </tr>
    <?php }} ?>
</table>
