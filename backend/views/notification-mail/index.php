<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NotificationMailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notification Mails';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-mail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Notification Mail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'mail_type',
            'days',
//            'send_mail',
            [
                'attribute' => 'send_mail',
                'label' => 'send_mail',
                'value' => function ($data) {
                    if ($data['send_mail']==1) {
                        $data['send_mail']= 'enable';
                        return $data['send_mail'];
                    } else {
                        $data['send_mail']= 'disable';

                        return $data['send_mail'];
                    }
                },
            ],
            'subject',
            'marketplace',
            'email_template:email',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
