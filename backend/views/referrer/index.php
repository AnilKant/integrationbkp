<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\components\Referral;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReferrerUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Referrer Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referrer-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a('Create Referrer User', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'username',
            'password',
            'merchant_id',
            // 'code',
            // 'created_at',
            // 'updated_at',
            // 'status',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'ACTION', 
                //'headerOptions' => ['width' => '80'],
                'template' => '{view}{approve}{unapprove}{redeem}{referral}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $options = ['data-pjax' => 0, 'title' => 'View', 'class' =>'view'];
                       
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"> </span>',
                            $url, $options);
                    },
                    'approve' => function ($url, $model) {
                        if ($model->status == '0' || $model->status == '2') {
                            $options = ['data-pjax' => 0, 'title' => 'Approve'];

                            return Html::a(
                                '<span class="glyphicon glyphicon-ok"> </span>',
                                $url, $options
                            );
                        }
                    },
                    'unapprove' => function ($url, $model) {
                        if ($model->status == '0' || $model->status == '1') {
                            $options = ['data-pjax' => 0, 'title' => 'Unapprove'];

                            return Html::a(
                                '<span class="glyphicon glyphicon-remove"> </span>',
                                $url, $options
                            );
                        }
                    },
                    'redeem' => function ($url, $model) {
                        if (Referral::getRedeemRequestCount($model->id)) {
                            $options = ['data-pjax' => 0, 'title' => 'Redeem Requests'];

                            return Html::a(
                                '<span class="glyphicon glyphicon-gift"> </span>',
                                Url::toRoute(['referrer-redeem/index', 'referrer_id'=>$model->id]), $options
                            );
                        }
                    },
                    'referral' => function ($url, $model) {
                        if (Referral::getReferralCount($model->id)) {
                            $options = ['data-pjax' => 0, 'title' => 'Referrals'];

                            return Html::a(
                                '<span class="glyphicon glyphicon-share"> </span>',
                                Url::toRoute(['referral/index', 'referrer_id'=>$model->id]), $options
                            );
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>
