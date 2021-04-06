<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
$app_selection =\yii\helpers\Url::toRoute(['get-apps']);

$this->registerJsFile(
    '@web/js/select2.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerCssFile("@web/css/select2.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()]
], 'css');
$email_prefrence = ['Feedback','News Feed','Follow-ups','Subscription Plan','Promotion and Offers','Promotion and Offer Calls'];
?>
<div class="export-merchant">
    <h1>Export Merchant</h1>
    <div class="export-merchant">
        <form id="export-merchant-form" action="<?= \yii\helpers\Url::toRoute(['export-merchant-details/export-csv']);?>" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <div class="form-group field-export-merchant-form">
                <label class="control-label" for="export-merchant-type">App Type</label>
                <select id="export-merchant-type" name="Export[app_type]" class="form-control" onchange="appSelection(this.value)">
                    <option value="new">New Apps</option>
                    <option value="old">Old Apps</option>
                </select>
                <div class="help-block"></div>
            </div>
            <div id="choose-apps">
                <label class="control-label" for="choose-app">Choose Apps</label>
                <select id="app_type" required class="form-control" name="Export[app_name][]" multiple="multiple"></select>
            </div>
            <div>
                <label class="control-label" for="choose-app">Choose Email Prefrences</label>
                <select id="email_option" required class="form-control" name="Export[email_prefrence][]" multiple="multiple">
                    <?php foreach ($email_prefrence as $value):?>
                        <option value="<?= $value?>"><?=$value?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-12"><input type="submit" class="btn btn-primary" value="Export CSV"></div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        var app_type = $('#export-merchant-type').val();
        appSelection(app_type)
        $('#email_option').select2();
    })
    function appSelection(value) {
        $('#LoadingMSG').show();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var url='<?=$app_selection?>';
        $.ajax({
            method: "post",
            url: url,
            data: {app_type: value, _csrf: csrfToken}
        })
        .done(function (msg) {
            $('#LoadingMSG').hide();
            $('#app_type').html(msg);
        });
        $('#app_type').select2();
    }
</script>
