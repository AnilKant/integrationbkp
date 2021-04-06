<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\IntegrationLoginHelper;
use backend\models\NewappsErrorNotification;

$urlDelete = \yii\helpers\Url::toRoute(['error-notification/delete']);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NewappsErrorNotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Newapps Error Description';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-notification-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="pull-right">
        <?= Html::a('Add New Error', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="list-page">
        Show per page
        <select onchange="selectPage(this)" class="form-control"
                style="display: inline-block; width: auto; margin-top: 0px; margin-left: 5px; margin-right: 5px;"
                name="per-page">
            <option value="25" <?php if (!isset($_GET['per-page']) || isset($_GET['per-page']) && $_GET['per-page'] == 25) {
                echo "selected=selected";
            } ?>>25
            </option>
            <option <?php if (isset($_GET['per-page']) && $_GET['per-page'] == 50) {
                echo "selected=selected";
            } ?> value="50">50
            </option>
            <option value="100" <?php if (isset($_GET['per-page']) && $_GET['per-page'] == 100) {
                echo "selected=selected";
            } ?> >100
            </option>
        </select>
        Items
    </div>
    <?php
    $helper = new IntegrationLoginHelper();
    $jump_pageInputId = 'pager-custompage';
    $modules = $helper->getMarketplaceIntegrationModules();
    $mpFilterArray = [];
    $error_type_array = [
        NewappsErrorNotification::PRODUCT_ERROR => 'Product Error',
        NewappsErrorNotification::PRICE_ERROR => 'Price Error',
        NewappsErrorNotification::INVENTORY_ERROR => 'Inventory Error',
        NewappsErrorNotification::ORDER_ERROR => 'Order Error'
    ];
    foreach ($modules as $module=>$classInfo):
        $mpFilterArray[$module] = $module;
    endforeach;
    ?>
    <?= GridView::widget([
        'id' => "walmart_error_description",
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => "select[name='" . $dataProvider->getPagination()->pageSizeParam . "'],input[name='" . $dataProvider->getPagination()->pageParam . "']",
        'pager' => [
            'class' => \liyunfang\pager\LinkPager::className(),
            'pageSizeList' => [25, 50, 100],
            'pageSizeOptions' => ['class' => 'form-control', 'style' => 'display: none;width:auto;margin-top:0px;'],
            'maxButtonCount' => 5,
        ],
        'columns' => [
            ['class' => 'yii\grid\ActionColumn'],
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'marketplace',
                'format'=>'raw',
                'value' => function($data) use ($mpFilterArray){
                    return  $data['marketplace'];
                },
                'filter'=>$mpFilterArray,
            ],
            [
                'attribute' => 'is_enable',
                'format'=>'raw',
                'value' => function($data){
                    return  $data['is_enable']?'Enabled':'Disabled';
                },
                'filter'=>[1=>'Enabled',0=>'Disabled'],
            ],
            [
                'label' => 'Error Type',
                'format' => 'raw',
                'attribute' => 'error_type',
                'value' => function ($data)  use ($error_type_array)
                {
                    if (array_key_exists($data['error_type'],$error_type_array))
                        return $error_type_array[$data['error_type']];
                    else
                        return "Product Error";
                },
                'filter'=>$error_type_array,
            ],
            'error_code',
            [
                'label' => 'Error Descritpion',
                'format' => 'raw',
                'value' => function ($data)
                {
                    $data['error_description'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['error_description']);
                    return $data['error_description'];
                }
            ],
            [
                'label' => 'Error Solution',
                'format' => 'raw',
                'value' => function ($data)
                {
                    $data['error_solution'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['error_solution']);
                    return $data['error_solution'];
                }
            ],
        ],
    ]); ?>

</div>
<div id="merchant_register" style="display:none"></div>
<div id="confirm_delete" class="modal fade" style="display:none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Are you sure you want to delete this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="delete-yes">Yes</button>
                <button type="button" class="btn" id="individualsync-cancel" data-dismiss="modal">No
                </button>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    function selectPage(node) {
        var value = $(node).val();
        $('#walmart_error_description').children('select.form-control').val(value);
    }
    function confirmDelete(id,error_type)
    {

        $('#confirm_delete').modal('show');
        $("#confirm_delete").on('shown.bs.modal', function ()
        {
            $('#delete-yes').on('click', function () {

                var url = '<?= $urlDelete ?>';
                $.ajax({
                    method: "post",
                    url: url,
                    data: {id: id, error_type: error_type, _csrf: csrfToken}
                })
                    .done(function (msg) {
                        // console.log(msg);

                    });
            });
        });
    }
</script>
