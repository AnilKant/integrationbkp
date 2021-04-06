<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Referral;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReferralUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Referral Users';
$referrer_id = Yii::$app->request->get('referrer_id', false);
if($referrer_id) {
    $shopName = Referral::getShopUrlFromReferrerId($referrer_id);
    $this->title .= ' of "'.$shopName.'"';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referral-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a('Create Referral User', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'referrer_id',
            'merchant_id',
            [
                'attribute' => 'user_shopname',
                'label' => 'Referral Store Name',
                'format' => 'html',
                'value' => function ($data) {
                        //var_dump($data);die;
                        if (property_exists($data, 'user_username') && property_exists($data, 'user_shopname')) {
                            return '<a title="'.$data['user_shopname'].'" href="http://'.$data['user_username'].'" target="_blank">'.$data['user_shopname'].'</a>';
                        } else {
                            return '';
                        }
                    },
            ],
            'app',
            'status',
            // 'installation_date',
            // 'payment_date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>

</div>
