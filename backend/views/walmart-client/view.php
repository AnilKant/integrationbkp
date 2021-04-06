<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LatestUpdates */

$this->title = $model->fname.' '.$model->lname;
$this->params['breadcrumbs'][] = ['label' => 'Walmart Client Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="latest-updates-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->merchant_id], ['class' => 'btn btn-primary']) ?>
        <?php /*echo Html::a('Delete', ['delete', 'id' => $model->merchant_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'merchant_id',
            'fname',
            'lname',
            'legal_company_name',
            'store_name',
            'mobile',
            'email',
            'annual_revenue',
            'website',
            'amazon_seller_url',
            'position_in_company',
            'shipping_source',
            'other_shipping_source',
            'product_count',
            'company_address',
            'country',
            'have_valid_tax',
            'usa_warehouse',
            'products_type_or_category',
            'selling_on_walmart',
            'selling_on_walmart_source',
            'other_selling_source',
            'contact_to_walmart',
            'approved_by_walmart',
            'reference',
            'other_reference',
            'time_zone',
            'time_slot'
        ],
    ]) ?>

</div>
