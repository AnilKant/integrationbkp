<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\DeletedUserData;
use backend\models\DeletedUserDataSearch;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeletedUserDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deleted User Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deleted-user-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php  //echo Html::a('Create Deleted User Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'merchant_id',
            'token',
            //'installed_on',
            [
                'attribute'=>'installed_on',
                'label'=>'Installed On',
                'value'=>'installed_on',
                
                'filter'=> ArrayHelper::map(DeletedUserData::find()->asArray()->all(), 'installed_on', 'installed_on'),      
            ],
            'shop_name',
             'email:email',
            // 'created_at',
             'phone_number',
             //'country',
            [
                'attribute'=>'country',
                'label'=>'Country',
                'value'=>'country',
                
                'filter'=> ArrayHelper::map(DeletedUserData::find()->asArray()->all(), 'country', 'country'),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".glyphicon-trash").hide();
    });
</script>