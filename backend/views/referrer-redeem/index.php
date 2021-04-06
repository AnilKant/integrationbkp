<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Referral;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReferrerRedeemRequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Redeem Requests';
$referrer_id = Yii::$app->request->get('referrer_id', false);
if($referrer_id) {
    $shopName = Referral::getShopUrlFromReferrerId($referrer_id);
    $this->title .= ' of "'.$shopName.'"';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referrer-redeem-requests-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a('Create Referrer Redeem Requests', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'merchant_id',
            [
                'attribute' => 'user_shopname',
                'label' => 'Store Name / User Name',
                'format' => 'html',
                'value' => function ($data) {
                        if (property_exists($data, 'user_username') && property_exists($data, 'user_shopname') && !empty($data['user_shopname'])) {
                            return '<a title="'.$data['user_shopname'].'" href="http://'.$data['user_username'].'" target="_blank">'.$data['user_shopname'].'</a>';
                        } else {
                            return '<span>'.$data['referrer_username'].'</span>';
                        }
                    },
            ],
            //'amount',
            //'redeem_method',
            'status',
            // 'created_at',
            // 'updated_at',
            //'data:ntext',
            [
                'label' => 'Redeem Option',
                'value' => function ($data) {
                    if (!is_null($data['data'])) {
                        $payment_data = json_decode($data['data'], true);
                        return $payment_data['redeem-option'];
                    } else {
                        return "-";
                    }
                },
            ],
            [
                'label' => 'Requested Amount',
                'value' => function ($data) {
                    if (!is_null($data['data'])) {
                        $payment_data = json_decode($data['data'], true);
                        if(isset($payment_data['amount'])) {
                            return '$'.$payment_data['amount'];
                        } else {
                            return $payment_data['months'].' month(s)';
                        }
                    } else {
                        return "-";
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'//'{view} {update} {delete}',
            ],
        ],
    ]); ?>

</div>
