<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\IntegrationLoginHelper;


$urlRegistered = \yii\helpers\Url::toRoute(['walmartshopdetails/view-merchant']);
$urlDelete = \yii\helpers\Url::toRoute(['error-notification/delete']);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ErrorNotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Error Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-notification-index">
    <h1><?= Html::encode($this->title) ?></h1>
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?/*= Html::a('Create Error Notification', ['create'], ['class' => 'btn btn-success']) */?>
    </p>
    <?php
        $helper = new IntegrationLoginHelper();
        $loginUrl = $helper->getLoginasMerchantUrl('walmart');
        $jump_pageInputId = 'pager-custompage';
    ?>
    <?= GridView::widget([
        'id' => "walmart_errors",
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{link} {delete}',
                'header' => 'Actions',
                'buttons' => [
                 'link' => function ($url, $model, $data) use ($loginUrl) {

                            return Html::a(
                                '<span class="glyphicon glyphicon-log-in"> </span>',
                                //"https://apps.cedcommerce.com/integration/walmart/site/managerlogin?ext=".$model->merchant_id."&enter=true",
                                $loginUrl . $model['merchant_id'],
                                ['title' => 'Login As','target' => '_blank']
                            );
                            return '<a data-pjax="0" href="">Login as</a>';
                    },
                    'delete' => function($model,$data) {
                        $options = ['data-pjax' => 0, 'onclick' => 'confirmDelete(this.id, ' . $data['error_type'] . ')', 'title' => 'View Product Detail', 'id' => $data['merchant_id']];
                         return Html::a(
                                '<span class="glyphicon glyphicon-trash"> </span>',
                                'javascript:void(0)', $options
                            );
                        /*return Html::a( '<span class="glyphicon glyphicon-trash"> </span>',['/error-notification/delete','id'=>$data['merchant_id'],'error_type'=>$data['error_type']],
                            [
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this data?',
                                    'method' => 'post',
                                ],
                            ]);*/
                        },
                    ]
            ],
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'merchant_id',
                'label' => 'Merchant Id',
                'value' => function ($data) {
                    return "<a href='javascript:void(0)' onclick=getMerchants(" . $data['merchant_id'] . ",'Registration')>" . $data['merchant_id'] . "</a>";
                },
                'format' => 'raw',
            ],
            'shop_url:url',
            'email:email',
            'error_count',
           
            /*'identifier',
            'identifier_type',
            'marketplace',*/
            // 'error_type',
            /*'reason:ntext',
            'data:ntext',*/
            // 'count',
            'created_at',
            // 'updated_at',
            
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
            $('#walmart_errors').children('select.form-control').val(value);
        }

    function getMerchants(id, param) {
            var url = '<?= $urlRegistered ?>';
            $('#LoadingMSG').show();
            $.ajax({
                method: "post",
                url: url,
                data: {id: id, param: param, _csrf: csrfToken}
            })
                .done(function (msg) {
                    // console.log(msg);
                    $('#LoadingMSG').hide();
                    $('#merchant_register').html(msg);
                    $('#merchant_register').css("display", "block");
                    $('#merchant_register #myModal').modal('show');
                });
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
