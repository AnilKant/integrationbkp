<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 22/8/17
 * Time: 11:52 AM
 */
use yii\helpers\Html;
$succes_img = yii::$app->request->baseUrl . '/static/modules/walmart/assets/images/batchupload/fam_bullet_success.gif';
$error_img = yii::$app->request->baseUrl . '/static/modules/walmart/assets/images/batchupload/error_msg_icon.gif';
$loader_img = yii::$app->request->baseUrl . '/static/modules/walmart/assets/images/batchupload/rule-ajax-loader.gif';

$url = \yii\helpers\Url::toRoute(['walmartshopdetails/get-validate-data']);
$backUrl = \yii\helpers\Url::toRoute(['walmartshopdetails/index']);


?>
<style type="text/css">
    .shopify-api ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .shopify-api ul li {
        margin-left: 0;
        border: 1px solid #ccc;
        margin: 2px;
        padding: 2px 2px 2px 2px;
        font: normal 12px sans-serif;
    }

    .shopify-api img {
        margin-right: 5px;
    }

    li span ul li {
        border: 0px !important;
        margin-left: 18px !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="form new-section sync-shopify-product">
            <div class="tophatter-pages-heading">
                <div class="title-need-help">
                    <h1 class="tophatter_Products_style">Merchant Validating..</h1>
                </div>
                <div class="product-upload-menu">
                    <a href="<?= $backUrl; ?>">
                        <button class="btn btn-primary uptransform" type="button" title="Back"><span>Back</span>
                        </button>
                    </a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="block-content shopify-api ">
                <ul class="warning-div" style="margin-top: 18px">
                    <li style="background-color:#Fff;">
                        <img src="<?php echo yii::$app->request->baseUrl . '/static/modules/walmart/assets/images/batchupload/note_msg_icon.gif'; ?>"
                             class="v-middle" style="margin-right:5px"/>
                        Starting Merchant Counting, please wait...
                    </li>
                    <li style="background-color:#FFD;">
                        <img src="<?php echo yii::$app->request->baseUrl . '/static/modules/walmart/assets/images/batchupload/fam_bullet_error.gif'; ?>"
                             class="v-middle" style="margin-right:5px"/>
                        Warning: Please do not close the window during merchants count process.
                    </li>
                </ul>

                <ul id="profileRows">
                    <li style="background-color:#DDF; ">
                        <img class="v-middle" src="<?php echo $succes_img ?>">
                        <?php echo 'Total ' . $totalcount . ' merchants found on our App'; ?>
                    </li>
                    <li style="background-color:#DDF;" id="update_row">
                        <img class="v-middle" id="status_image" src="<?php echo $loader_img ?>">
                        <span id="update_status" class="text">Validating...</span>
                    </li>
                    <li id="liFinished" style="display:none;background-color:#Fff;">
                        <img src="<?php echo yii::$app->request->baseUrl . '/static/modules/walmart/assets/images/batchupload/note_msg_icon.gif'; ?>"
                             class="v-middle" style="margin-right:5px"/>
                        Finished merchant validation process.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var csrfToken = $('meta[name=csrf-token]').attr("content");
    var totalRecords = parseInt("<?php echo $totalcount; ?>");
    var pages = parseInt("<?php echo $pages; ?>");
    var countOfSuccess = 0;
    var id = 0;
    var my_id = document.getElementById('liFinished');
    var update_status = document.getElementById('update_status');
    var update_row = document.getElementById('update_row');
    var status_image = document.getElementById('status_image');
    countmerchant();

    function countmerchant() 
    {
        percent = getPercent();
        update_status.innerHTML = percent + '% Page ' + (id + 1) + ' Of total merchant pages ' + pages + ' Processing';

        $.ajax({
            url: "<?= $url?>",
            method: "post",
            dataType: 'json',
            data: {index: id, _backendCSRF: csrfToken,total :totalRecords },
            success: function (json) 
            {
                console.log(json);
                id++;
                if (json.success) 
                {
                    countOfSuccess += json.success_count;
                    var span = document.createElement('li');
                    span.innerHTML = '<img class="v-middle" src="<?php echo $succes_img ?>"><span class="text">' + json.success_count + ' Merchant found on page ' + id + '.</span>';
                    span.id = 'id-' + id;
                    span.style = 'background-color:#DDF';
                    update_row.parentNode.insertBefore(span, update_row);

                }

                if (json.error) {
                    var span = document.createElement('li');
                    span.innerHTML = span.innerHTML + '<img class="v-middle" src="<?php echo $error_img ?>"><span class="text">' + json.error_count + ' Product(s) Having Sku(s) ' + json.erroredSkus + ' Have Some Errors/Missing Info.' + '</span>';
                    span.style = 'background-color:#FDD';
                    update_row.parentNode.insertBefore(span, update_row);
                }

                if (id < pages) {
                    countmerchant();
                }
                else {
                    status_image.src = '<?php echo $succes_img ?>';
                    var span = document.createElement('li');
                    span.innerHTML = '<img class="v-middle" src="<?php echo $succes_img ?>"><span id="update_status" class="text">' + countOfSuccess + ' Merchants found on app.' + '</span>';
                    span.style = 'background-color:#DDF';
                    my_id.parentNode.insertBefore(span, my_id);

                    document.getElementById("liFinished").style.display = "block";
                    $(".warning-div").hide();
                    $("#profileRows").css({'margin-top': '10px'});
                    update_status.innerHTML = percent + '% ' + (id) + ' Of ' + pages + ' Processed.';

                    var csv_url = "<?= $csv_path ?>";

                    if (csv_url != '') {
                        setTimeout(function () {
                            window.location.href = '<?php echo $csv_path; ?>';
                        }, 500);
                    }
                }
            },
            error: function () {
                
                id++;
                var span = document.createElement('li');
                span.innerHTML = '<img class="v-middle" src="<?php echo $error_img ?>"><span class="text">Something Went Wrong.</span>';
                span.id = 'id-' + id;
                span.style = 'background-color:#FDD';
                update_row.parentNode.insertBefore(span, update_row);

                if (id < pages) {
                    countmerchant();
                }
                else {
                    status_image.src = '<?php echo $succes_img ?>';
                    var span = document.createElement('li');
                    span.innerHTML = '<img class="v-middle" src="<?php echo $succes_img ?>"><span id="update_status" class="text">' + countOfSuccess + ' Merchants found on our App.' + '</span>';
                    span.style = 'background-color:#DDF';
                    my_id.parentNode.insertBefore(span, my_id);

                    $(".warning-div").hide();
                    $("#profileRows").css({'margin-top': '10px'});
                    document.getElementById("liFinished").style.display = "block";
                }
            }
        });

    }

    function getPercent() {
        return Math.ceil(((id + 1) / pages) * 1000) / 10;
    }


</script>