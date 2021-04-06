<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\bonanza\models\bonanzaRegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bonanza Client Detail';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonanza-registration-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'merchant_id',
            'fname',
            'lname',
            //'store_name',
            // 'shipping_source',
            // 'other_shipping_source',
            'mobile',
            'email:email',
            // 'reference:ntext',
            // 'agreement',
            // 'other_reference',
             //'country',
            // 'selling_on_tophatter_source',
            // 'other_selling_source',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".glyphicon-pencil").hide();
        $(".glyphicon-trash").hide();
    });
</script>