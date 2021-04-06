<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\components\Data;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoFruugoInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Magento Onbuy Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="magento-onbuy-info-view">

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
}} ?>
</table>
