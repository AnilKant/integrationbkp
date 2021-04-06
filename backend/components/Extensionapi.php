<?php 
namespace backend\components;
use yii\base\Component;
use yii\helpers\Url;
use backend\components\Parser;
use frontend\components\Data;


class Extensionapi extends component
{    
    /**
     * Get Option Values Simple Product
     */
    public static function getClientsDetails($storeUrl="",&$plateform="")
    {
        $url = "";
        if (($plateform=='m1')||($plateform=='m2')) {
            $url= $storeUrl."/walmart/walmartanalytics/getAnalytics"; 
            $plateform = "magento";   
        }elseif($plateform=='woocommerce') {
            $url = $storeUrl."/woocommerce/walmart/walmartanalytics/getAnalytics";    
            $plateform = "woocommerce";   
        }else{
            return "please choose plateform";
        }
        
        $ch = curl_init($url);
                
        curl_setopt($ch, CURLOPT_HEADER, 1); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $body = substr($server_output, $header_size);
        curl_close ($ch);
        return $body;
    }
    public static function getFruugoClientsDetails($storeUrl,&$plateform="",$last_updated)
    {
        $url = "";
        $date = date("Y-m-d h:i:s");
        if (($plateform=='Magento2')) {
            $url= $storeUrl."rest/getrevenuedata"; 
            $plateform = "magento";   
        }elseif($plateform=='Magento1') {
            $url = $storeUrl."fruugo/rest/getrevenuedata";    
            $plateform = "Magento1";   
        }elseif($plateform=='PrestaShop') {
            $url = $storeUrl."modules/cedfruugo/dashboard.php";    
            $plateform = "PrestaShop";   
        }elseif($plateform=='Woocommerce') {
            $url = $storeUrl."/wp-content/plugins/woocommerce-fruugo-integration/includes/ced_order_product.php";    
            $plateform = "Woocommerce";   
        }elseif($plateform=='Shopify') {
            $url = "https://apps.cedcommerce.com/marketplace-integration/fruugo/fruugo-api/sync-order-data";   
            $plateform = "Shopify";   
        }elseif($plateform=='Bigcommerce') {
            $url = "https://connector.cedcommerce.com/fruugo/fruugo-api/sync-order-data";   
            $plateform = "Bigcommerce";   
        }else{
            return "please choose plateform";
        }
        
        if($platform=='Shopify' || $platform='Bigcommerce')
        {
            $url = $url."?shop=".$storeUrl."&from=".rawurlencode($last_updated)."&to=".rawurlencode($date);
        }
        else
        {
            $url = $url."?from=".rawurlencode($last_updated)."&to=".rawurlencode($date);
        }
         
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec ($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($server_output, $header_size);
        curl_close ($ch);
        return $body;
    }

    public static function CGetRequest($username,$password,$url)
    {
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username .":". $password);
        $serverOutput = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header = substr($serverOutput, 0, $header_size);
        if ($httpcode ==400 || $httpcode ==401) {
                return false;
        }
        $body = substr($serverOutput, $header_size);
        curl_close($ch);
        return $serverOutput;
    }
    /*get order from fruugo*/
    public static function getOrders($username,$password,$cleintId){
        
        $date = "2018-04-01&to=2018-04-30";//date('Y-m-d',strtotime("-10 days"));
        $url = 'https://www.fruugo.com/orders/download?from='.$date;
        $response = self::CGetRequest($username,$password,$url);
        //$orderData = (array)simplexml_load_string($response);
        if (!$response) {
           return false;
        }
        $parsXml = new Parser();
        $orderData = $parsXml->loadXml($response)->xmlToArray(); 
        if(isset($orderData['o:orders']['_value']['o:order']))
        {
            $order_Data=[];
            $i=0;
            foreach ($orderData['o:orders']['_value']['o:order'] as $order) 
            {
            
                
                if (!isset($order['o:orderLines']['o:orderLine'][0])) {
                    $order['o:orderLines']['o:orderLine'] = [
                        0 => $order['o:orderLines']['o:orderLine'],
                    ];
                }
                $orderTotal = $total_tax =0;
                $fruugo_order_id=$order['o:orderId'];
                $order_place_date = $order['o:orderReleaseDate'];
                $total_paid=0;
                $sql = "SELECT `id`, `merchant_info_id`, `fruugo_order_id`, `order_total`, `order_date`, `created_at` FROM `magento_fruugo_orders` WHERE `fruugo_order_id`=".$fruugo_order_id;
                $order_data = Data::sqlRecords($sql,'one','select'); 
                if (!$order_data) {
                    foreach ($order['o:orderLines']['o:orderLine'] as $value) {
                        $total_paid += $value['o:totalPriceInclVat'];
                    }
                    $query = "INSERT INTO `magento_fruugo_orders`(`merchant_info_id`, `fruugo_order_id`, `order_total`, `order_date`) VALUES ('".$cleintId."','".$fruugo_order_id."','".$total_paid."','".$order_place_date."')";
                    Data::sqlRecords($query,null,'insert');
                    $i++;
                }
                
                
            }
        }

        return $i;
    }

}
?>
