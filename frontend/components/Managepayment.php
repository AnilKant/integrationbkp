<?php
namespace frontend\components;

use Yii;
use yii\base\Component;
use frontend\modules\walmart\components\Dashboard\Productinfo;
class Managepayment extends component
{
    public static $_totalProducts = null;
    /**
    * define product import range
    * @return int
    */
    public static function getCurrentPlan($plan_id)
    {
            $data = [
                '1' => '10000',
                '2' => '20000',
                '3' => '30000',
                '4' => '40000',
                '5' => '50000',
                '6' => '60000',
                '7' => '70000',
                '8' => '80000',
                '9' => '90000',
                '10' => '100000',
            ];
            return $data[$plan_id];
    }
    /**
    * var config_plan_id store plan id
    * @return int
    */
    public static function valadateProductImportRange($config_plan_id,$merchant_id,$marketPlace_name="walmart")
    {
        return true;
        $total_product = '';
        if($marketPlace_name=='walmart'){
            $total_product = Productinfo::getAllProducts($merchant_id);
        }
        if(!empty($total_product)){
            $product_plan_range = self::getCurrentPlan($config_plan_id);
            if($product_plan_range > $total_product){
                return true;
            }

        }
        
       return false; 
    }

    public static function updateTotalProductCache($merchant_id,$minus_operator=false)
    {
        
        $key = $merchant_id.'all_products';
        $total_products = \Yii::$app->cache->get($key);
        if($total_products && !is_null($total_products) && $total_products>0){
            if($minus_operator)
            {
                $total_products -=1;
            }
            else
            {
                $total_products +=1;
            }
            \Yii::$app->cache->set($key,$total_products);
        }
    }
}
?>