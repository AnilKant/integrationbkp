<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\components\Data;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MagentoOnbuyInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Magento Onbuy Infos';
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="magento-onbuy-info-index">

   <h1><?= Html::encode($this->title) ?></h1>
    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'domain',
            'email:email',
            'live_sku',
            'uploaded_sku',
            
            'install_on',
            'framework',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>


<script type="text/javascript">
    $(document).ready(function(){
        $(".glyphicon-trash").hide();
    });
</script>