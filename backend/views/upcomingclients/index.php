<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UpcomingClientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Upcoming Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upcoming-clients-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            'email:email',
            'description:ntext',            
            [
                'format'=>'raw',
                'attribute' => 'is_checked',                
                'filter'=>["Yes"=>"Yes","No"=>"No"],
            ],
             'date',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action','headerOptions' => ['width' => '80'],
                'template' => '{update}{view}',
            ],
        ],
    ]); ?>

</div>
