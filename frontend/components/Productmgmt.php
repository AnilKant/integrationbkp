<?php
namespace frontend\components;

use Yii;
use yii\base\Component;

class Productmgmt extends Component
{
	/**
	* $appObject  app object accroding to marketplace(shopifyClientsHepler class object)
	* $product_id shopify product id 
	* $inventoryConfigSetting inventory config setting is enable or disable
	* @return bool
	*/
	public static function checkProductOnShopify($appObject,$variant_id,$ordered_qty)
    {
                $productData = $appObject->call('GET', '/admin/variants/'.$variant_id.'.json');
                if(isset($productData['errors']))
                {
                   return ['success'=>false,'message'=>'sku_not_available'];/*product not exist in shopify store*/
                }
                else 
                {
                    if(isset($productData['inventory_management']) && $productData['inventory_management'] == 'shopify'){

                        if(isset($productData['inventory_policy']) && $productData['inventory_policy'] == 'continue')
                        {
                            return ['success'=>true,'message'=>'Allow by customer order placed when product is  out of stock']; /* Allow by customer order placed when product is  out of stock*/
                        }
                        else
                        {
                            if(isset($productData['inventory_quantity']) && $productData['inventory_quantity'] >= $ordered_qty)
                            {
                                return ['success'=>true,'message'=>'Order quantity within range'];
                                /*Order quantity within range*/
                            }
                            else{
                                return ['success'=>false,'message'=>'qty_not_available'];
                                /*Order quantity greater than existing product quantity*/
                            }
                        }
                    }
                    else{
                        return ['success'=>true,'message'=>'Don\'t track inventory by shopify'];
                        /*Don't track inventory by shopify*/
                    }

                }
                return ['success'=>true,'message'=>''];
    }

    /**
     * Get Option Values Simple Product
     */
    public static function getOptionValuesForSimpleProduct($product)
    {
        $options = [];
        if (is_array($product) && isset($product['variants'])) {
            $variant = reset($product['variants']);
            if (isset($product['options'])) {
                foreach ($product['options'] as $value) {
                    if ($value['name'] != 'Title') {
                        $options[$value['name']] = $variant['option' . $value['position']];
                    }
                }
            }
        }

        if (count($options))
            return json_encode($options);
        else
            return '';
    }
}