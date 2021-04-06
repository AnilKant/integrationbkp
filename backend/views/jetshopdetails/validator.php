<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\components\Data;
$urlJet = \yii\helpers\Url::toRoute(['jetshopdetails/validationresponse']);
$urlResponse = \yii\helpers\Url::toRoute(['jetshopdetails/validationresult']);
/* @var $this yii\web\View */
/* @var $model app\models\JetExtensionDetail */

$this->title = 'Product Validation';

$this->params['breadcrumbs'][] = ['label' => 'Jet-Extension', 'url' => ['validation']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="validator-div">
    <div class="sku-validation">
        <h2>Product SKU validation</h2>
        <table>
            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                <tr>
                    <th>
                        Enter Merchant ID
                    </th>
                    <th>
                        Choose Table
                    </th>
                    <th>
                        Choose Column
                    </th>
                    <th>
                        Enter detail
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr id="search_body">
                    <td id="merchant_id_field">
                        <input type="text" name="merchant_id" id="merchant_id" />
                    </td>
                    <td id="table_name">
                        <select id="table_name" name="table_name" class="form-control" onchange="getColumns()">                            
                            <?php 
                                foreach($tablename as $key => $val)
                                {
                                    ?>
                                        <option value="<?=$val?>"><?=$val?></option>
                                    <?php
                                }
                            ?>
                        </select>                        
                    </td>                    
                </tr>
                <tr>
                    <td colspan="4">
                       &nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="center">
                        <input type="button" name="check_sku" value="Click to Validate" onclick="validateSku()"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>  
    <div id="response_div" style="display: none;">
    </div>  
</div>
<script type="text/javascript">
 var csrfToken = $('meta[name="csrf-token"]').attr("content");
 function getColumns()
 {
    var sql= "SHOW COLUMNS FROM "+$('select[name=table_name]').val();
    var url='<?= $urlJet ?>';
    $('#LoadingMSG').show();
    $.ajax({
      method: "post",
      url: url,
      data: {sql:sql,_csrf : csrfToken }
    })
    .done(function(msg) {
       $('#LoadingMSG').hide();
       $( "#search_body" ).append( msg );
    });
 }
 function validateSku()
 {
    var table_name = $('select[name=table_name]').val();
    var column_name = $('select[name=column_name]').val();
    var search_field = $("#search_name").val();
    var merchant_id = $("#merchant_id").val();

    var url_name ='<?= $urlResponse ?>';
    $('#LoadingMSG').show();
    $.ajax({
      method: "post",
      url: url_name,
      data: {merchant_id:merchant_id,table_name:table_name,column_name:column_name,search_field:search_field,_csrf : csrfToken }
    })
    .done(function(msg) {
       $('#LoadingMSG').hide();
       $('#response_div').html(msg);
       $('#response_div').css("display","block");   
    });
 }
</script>