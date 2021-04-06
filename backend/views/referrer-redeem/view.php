<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\components\Referral;

/* @var $this yii\web\View */
/* @var $model backend\models\ReferrerRedeemRequests */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Referrer Redeem Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referrer-redeem-requests-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php /*echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])*/ ?>
        <?php /*echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/ ?>
        <?php 
            if($model->status == 'pending') {
                if($model->redeem_method == 'paypal')
                    $label = 'Confirm Payment';
                else
                    $label = 'Approve Request';
                echo Html::a($label, ['confirm', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => 'Are you sure you want to confirm this request?',
                        'method' => 'post',
                    ],
                ]);
            }
        ?>
    </p>

    <?php /*echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'referrer_id',
            'amount',
            'redeem_method',
            'data:ntext',
            'status',
            'created_at',
            'updated_at',
        ],
    ])*/ ?>

    <?php $payment_data = json_decode($model->data, true); ?>

    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th>ID</th>
                <td><?= $model->id ?></td>
            </tr>
            <tr>
                <th>Referrer ID</th>
                <td><?= $model->referrer_id ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= $model->status ?></td>
            </tr>
            <tr>
                <th>Redeem Method</th>
                <td><?= $model->redeem_method ?></td>
            </tr>
        <?php if($model->redeem_method == 'paypal') { ?>
            <tr>
                <th>Requested Amount</th>
                <td>$<?= $payment_data['amount'] ?></td>
            </tr>
            <tr>
                <th>Referrer Name</th>
                <td><?= $payment_data['name'] ?></td>
            </tr>
            <tr>
                <th>Referrer Email</th>
                <td><?= $payment_data['email'] ?></td>
            </tr>
        <?php } elseif($model->redeem_method == 'subscription') { ?>
            <tr>
                <th>Requested Subscription Period</th>
                <td><?= $payment_data['months'] ?> month(s)</td>
            </tr>
            <tr>
                <th>Subscription App</th>
                <td><?= $payment_data['app'] ?></td>
            </tr>
        <?php if($payment_data['account']=='self') { 
                $shop_url = Referral::getShopUrlFromReferrerId($model->referrer_id);
              } else {
                $shop_url = $payment_data['other-account'];
              } 
        ?>
            <tr>
                <th>Shopify Store Url</th>
                <td><?= $shop_url ?></td>
            </tr>

        <?php }?>
            <tr>
                <th>Created At</th>
                <td><?= $model->created_at ?></td>
            </tr>
            <tr>
                <th>Updated At</th>
                <td><?= $model->updated_at ?></td>
            </tr>
        </tbody>
    </table>
</div>
