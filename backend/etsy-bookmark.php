<?php
use yii\helpers\Html;

$bookmarks = [
    'Reconfigure Bookmarks' => [
        'merchant-reconfigured' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/merchant-reconfigured',
        'force step-1 config' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-install/index?force-setup=1',
    ],
    'Product Bookmarks' => [
        'get-product' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/getproduct?id=',
        'getproduct' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/getproduct?variant_id=17030312001647&inventory_level=true',
        'get-inv-updates' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-inv-updates?product_id=',
        'get-all-listings from etsy' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-all-listings',
        'Total Products' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/totalproducts',
        'get-listing' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-listing?id=',
        'custom import by product Ids' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/product-import?ids=',
    ],
    'Order Bookmarks' => [
        'shopify order detail' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/getorderdetail?id=',
        'etsy order details' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-order?id=',
        'Delete order' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/deleteorder?id=',
        'get duplicate order using tags' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/order-gql?debug&id=',
        'open order details' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-order?status=1',
        'get previous-shopify-orders' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/getpast-shopify-orders?days=5&debug',
        'Etsy supported carriers' => 'https://www.etsy.com/developers/documentation/getting_started/seller_tools#section_supported_carriers',
    ],
    'Etsy related Bookmarks' => [
        'getall-payment-template' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/getall-payment-template',    
        'update-etsy-shop-json' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/update-etsy-shop-json',
        'remove-shop-section' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/remove-shop-section?profile_id=',
        'Sections etc' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/etsy-info',
        'multilanguage' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/multilanguage?index=occasion',
        'Etsy Developers' => 'https://www.etsy.com/developers/documentation',
        'Etsy API - Google Groups' => 'https://groups.google.com/forum/?utm_medium=email&utm_source=footer#!forum/etsy-api-v2',
        'Etsy - Apps You Use' => 'https://www.etsy.com/in-en/your/apps?ref=si_apps',
        'doc' => 'https://docs.google.com/document/d/1MXLNq50lLjmudE6RjgWBIfxwlDedg3pLG48q0trhW7s/edit',
        'Knowledge base | syncommerce' => 'https://syncommerce.groovehq.com/knowledge_base/categories/etsy-listing-errors',
        'Etsy Status' => 'https://www.etsystatus.com/#',
        'Technical Issues' => 'https://community.etsy.com/t5/Technical-Issues/bd-p/bugs',
    ],
    'Shopify related Bookmarks' => [
        'get shopify theme' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-theme',
        'get shopify auth scope' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/getscope',
        'get shopify locations' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-locations',
        'get shopify shop details' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-shop',    
        'get-billing' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-billing?type=one-time',
        'get webhooks' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/webhook?shop=dev-seller.myshopify.com',
    ],
    'Other Bookmarks' => [
        'shopify metafields' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/metafields?id=',
        'get-webhook' => 'https://apps.cedcommerce.com/marketplace-integration/etsy/etsy-api-status/get-webhook?debug',
        'currency conversion' => 'https://bonanza.cedcommerce.com/etsy/etsy-api-status/currency?from=INR&to=USD&debug',
    ],
    'Get webhook logs' => [
        'newapps_product_create -> shopify product_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_product_create&unique_id=4733406543953',
        'newapps_product_update -> shopify product_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_product_update&unique_id=4733406543953',
        'newapps_product_delete -> shopify product_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_product_delete&unique_id=4733406543953',
        'newapps_inventory_update -> shopify inventory_item_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_inventory_update&unique_id=34289052549201',
        'newapps_etsy_inventory_update  -> shopify product_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_etsy_inventory_update&unique_id=4711675789393',
        'newapps_bonanza_inventory_update -> shopify product_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_bonanza_inventory_update&unique_id=4711675789393',
        'newapps_locations_update -> shopify location_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_locations_update&unique_id=37048123473',
        'newapps_locations_delete -> shopify location_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_locations_delete&unique_id=37048123473',
        'newapps_order_update  -> shopify order_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_order_update&unique_id=2778084704337',
        'newapps_order_update  -> shopify order_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_fulfillments_update&unique_id=2778084704337',
        'newapps_shop_update  ->  shopify shop_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_shop_update&unique_id=1918992496',
        'newapps_uninstall_app -> shopify shop_id'=> 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_uninstall_app&unique_id=1918992496',
        'newapps_shop_erasure  -> shopify shop_id' => 'https://apps.cedcommerce.com/marketplace-integration/sqs/helper/get-logs?shop=dev-seller.myshopify.com&webhook_type=newapps_shop_erasure&unique_id=1918992496'
    ]         
];

?>
<head>
    <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        tr:nth-child(odd) {
          background-color: #dddddd;
        }
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
    </style>
</head>
<div id="accordion" class="panel-group">
    <?php foreach ($bookmarks as $type => $details) {
        $type_id = str_replace(' ', '', $type)
        ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#<?=$type_id?>"><?=$type?></a>
            </h4>
          </div>
          <div id="<?=$type_id?>" class="panel-collapse collapse in">
            <div class="panel-body">
                <table>
                    <?php 
                        foreach ($details as $bookmark_name => $url) {
                            ?>
                            <tr>
                                <td><?=$bookmark_name?></td>
                                <td><?=Html::a($url,$url,['target'=>'_blank'])?></td>
                            </tr>
                            <?php
                           
                        }                                                
                    ?>
                </table>
            </div>
          </div>
        </div>            
        <?php
    } ?>
</div>