<?php
namespace frontend\components;
use Yii;
use yii\base\Component;
use frontend\components\Signature;
use frontend\components\Jetproductinfo;
use frontend\components\Data;
use Xml\Parser;
use Xml\Generator;
use yii\base\Response;

class Walmartapi extends component
{
    const GET_ORDERS_SUB_URL = 'v3/orders';
    const GET_ORDERS_RELEASED_SUB_URL = 'v3/orders/released';
    const GET_ITEMS_SUB_URL = 'v2/items';
    const GET_FEEDS_SUB_URL = 'v2/feeds';
    const GET_FEEDS_ITEMS_SUB_URL = 'v2/feeds?feedType=item';
    const GET_FEEDS_INVENTORY_SUB_URL = 'v2/feeds?feedType=inventory';
    const GET_FEEDS_PRICE_SUB_URL = 'v2/feeds?feedType=price';
    const GET_INVENTORY_SUB_URL = 'v2/inventory';
    const GET_REPORTS_SUB_URL = 'v2/getReport';
    const UPDATE_PRICE_SUB_URL = 'v2/prices';
    
    public $apiUrl;
    public $apiConsumerId;
    public $apiConsumerChannelId;
    public $apiPrivateKey;
    public $apiSignature;
    //public $attributes;

    public function __construct($apiConsumerId="",$apiPrivateKey="",$apiConsumerChannelId="") 
    {
        $this->apiUrl = "https://marketplace.walmartapis.com/";
        $this->apiConsumerId = $apiConsumerId;
        $this->apiPrivateKey = $apiPrivateKey;
        $this->apiConsumerChannelId = $apiConsumerChannelId;
        $this->apiSignature = new Signature();
        //$this->attributes = new Attributes();
        //$this->xml = new Generator();
    }

    public function postRequest($url, $params)
    {
        $signature = $this->apiSignature->getSignature($url,'POST',$this->apiConsumerId,$this->apiPrivateKey);
        $url =  $this->apiUrl . $url;
        $body='';
        if (isset($params['file'])) {
            $body['file'] = new \CurlFile($params['file'], 'application/xml');
        } elseif (isset($params['data'])) {
            $body = $params['data'];
        }
        
        $headers = [];
        $headers[] = "WM_SVC.NAME: Walmart Marketplace";
        $headers[] = "WM_QOS.CORRELATION_ID: " . base64_encode(\phpseclib\Crypt\Random::string(16));
        $headers[] = "WM_SEC.TIMESTAMP: " . $this->apiSignature->timestamp;
        $headers[] = "WM_SEC.AUTH_SIGNATURE: " . $signature;
        $headers[] = "WM_CONSUMER.ID: " .  $this->apiConsumerId;
        if (isset($params['file']) && !empty($params['file'])) {
            $headers[] = "Content-Type: multipart/form-data;";
        } elseif (isset($params['data']) && !empty($params['data'])) {
            $headers[] = "Content-Type: application/xml";
        } else {
            $headers[] = "Content-Type: application/json";
        }
        $headers[] = "Accept: application/xml";
        if (isset($params['headers']) && !empty($params['headers'])) {
            $headers[] = $params['headers'];
        }
        $headers[] = "HOST: marketplace.walmartapis.com";

        $ch = curl_init($url);
        if($body)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        }
        else
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS,NULL);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        curl_close ($ch);

        return $response;
   
    }

    /**
     * Post Request on https://marketplace.walmartapis.com/
     * @param string $url
     * @param string|[] $params
     * @return string
     */
    public function getRequest($url, $params = [])
    {
        $signature = $this->apiSignature->getSignature($url,'GET',$this->apiConsumerId,$this->apiPrivateKey);
        $url = $this->apiUrl . $url;

        $headers = [];
        $headers[] = "WM_SVC.NAME: Walmart Marketplace";
        $headers[] = "WM_QOS.CORRELATION_ID: " . base64_encode(\phpseclib\Crypt\Random::string(16));
        $headers[] = "WM_SEC.TIMESTAMP: " . $this->apiSignature->timestamp;
        $headers[] = "WM_SEC.AUTH_SIGNATURE: " . $signature;
        $headers[] = "WM_CONSUMER.ID: " .  $this->apiConsumerId;
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/xml";
        if (isset($params['headers']) && !empty($params['headers'])) {
            $headers[] = $params['headers'];
        }
        $headers[] = "HOST: marketplace.walmartapis.com";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        curl_close ($ch);
        return $response;
    }

    /**
     * Get a Order
     * @param string $purchaseOrderId
     * @param string $subUrl
     * @return array|string
     */
    public function getOrder($purchaseOrderId, $subUrl = self::GET_ORDERS_SUB_URL )
    {
        $response = $this->getRequest($subUrl . '?purchaseOrderId=' . $purchaseOrderId);
        return $response;


    }

    /**
     * Get Orders
     * @param string|[] $params - date in yy-mm-dd
     * @param string $subUrl
     * @return string
     * @link  https://developer.walmartapis.com/#get-all-orders
     */
    public function getOrders($params = ['createdStartDate' => '2016-01-01'], $subUrl = self::GET_ORDERS_SUB_URL)
    {
        if (count($params) > 0) {
            $count = 0;
            foreach ($params as $param => $value) {
                if ($count == 0) {
                    $subUrl .= '?' . $param . '=' . $value;
                } else {
                    $subUrl .= '&' . $param . '=' . $value;
                }
                $count += 1;
            }
        }
        $response = $this->getRequest($subUrl,
            ['headers' => 'WM_CONSUMER.CHANNEL.TYPE: ' . $this->apiConsumerChannelId]);
//        return $response;
        try{
            return $response;
        }
        catch(Exception $e)
        {
            return $response;
        }

    }

    /**
     * Get Reports
     * @param string|[] $params
     * @param string $subUrl
     * @return string compressed csv file
     * @link https://developer.walmartapis.com/#get-report
     */
    public function getReports($params = [], $subUrl = self::GET_REPORTS_SUB_URL)
    {
        if (!isset($params['type']) || empty($params['type'])) {
            $params['type'] = 'item';
        }
        $queryString = empty($params) ? '' : '?' . http_build_query($params);
        $response = $this->getRequest($subUrl . $queryString);
        //csv file in response
        return $response;
    }
    /**
     * Get Item
     * @param string $sku
     * @param string $subUrl
     * @return []
     * @link https://developer.walmartapis.com/#get-an-item
     */
    public function getItem($sku, $returnField = null, $subUrl = self::GET_ITEMS_SUB_URL )
    {
        $response = $this->getRequest($subUrl . '?sku=' . $sku);
        try {
            $response = json_decode($response,true);
            if ($returnField) {
                return $response['MPItemView'][0]['publishedStatus'];
            }
            return $response;
        }
        catch(Exception $e){
            return false;
        }
    }

    /**
     * Get Items
     * @param string|[] $params
     * @param string $subUrl
     * @return string
     * @link https://developer.walmartapis.com/#get-all-items
     */
    public function getItems($params = [], $subUrl = self::GET_ITEMS_SUB_URL)
    {
        if (!isset($params['limit']) || empty($params['limit'])) 
        {
            $params['limit'] = '20';
        }
        $queryString = empty($params) ? '' : '?' . http_build_query($params);
        $response=$this->getRequest($subUrl . $queryString);
        //var_dump($response);
        if(self::is_json($response))
        {
            return json_decode($response,true);
        }
        else
        {
            return self::xmlToArray($response);
        }
        //return $response;
    }

    /**
     * Get Inventory
     * @param string $sku
     * @param string $subUrl
     * @return string
     * @link https://developer.walmartapis.com/#get-inventory-for-an-item
     */
    public function getInventory($sku, $subUrl = self::GET_INVENTORY_SUB_URL)
    {
        $response = $this->getRequest($subUrl . '?sku=' . $sku);
        return json_decode($response,true);
    }

    /**
     * Get Feeds
     * @param null $feedId
     * @param string $subUrl
     * @return string
     * @link https://developer.walmartapis.com/#feeds
     */
    public function getFeeds($feedId = null, $subUrl = self::GET_FEEDS_SUB_URL)
    {
        if ($feedId != null) 
        {
            $response = json_decode($this->getRequest($subUrl . '?feedId=' . $feedId),true);
            return $response;
        }
        $response = json_decode($this->getRequest($subUrl),true);
        return $response;

    }
    /**
     * To Convert Escaped Characters in XML to HTML chars
     * @param string $path
     * @return bool
     */
    public static function unEscapeData($path)
    {
        if (file_exists($path)) 
        {
           $handle = fopen($path, "r");
           $contents = fread($handle, filesize($path));
           $data = htmlspecialchars_decode($contents);
           fclose($handle);
           $fileOrig=fopen($path,'w');
           fwrite($fileOrig, $data);
           fclose($fileOrig);
        }
        return false;
    }
    /**
     * Create Product on Walmart
     * @param string|[] $ids
     * @return bool
     */
    
    public function createProductOnWalmart($ids,$walmartHelper,$merchant_id,$connection)
    {
        $timeStamp = (string)time();
        $productToUpload = [
            'MPItemFeed' => [
                '_attribute' => [
                    'xmlns' => 'http://walmart.com/',
                    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                    'xsi:schemaLocation' => 'http://walmart.com/ MPItem.xsd',
                ],
                '_value' => [
                    0 => [
                        'MPItemFeedHeader' => [
                        'version' => '2.1',
                        'requestId' => $timeStamp,
                        'requestBatchId' => $timeStamp,
                        ],
                    ]
                
                ],
            ]
        ];
        if (count($ids) > 0 )
        {
            $error=[];
            $key = 1;
            $uploadProductIds=[];
            $successXmlCreate=0;
            foreach ($ids as $id) 
            {
                $query='select product_id,title,sku,type,wal.product_type,description,image,qty,price,weight,vendor,upc,walmart_attributes,category,tax_code,short_description,self_description from `walmart_product` wal INNER JOIN `jet_product` jet ON jet.id=wal.product_id where wal.product_id="'.$id.'" LIMIT 1';
                $productArray = Data::sqlRecords($query,"one","select");
                $validateResponse=[];
                $validateResponse = self::validateProduct($productArray,$connection);
                //var_dump($validateResponse);die;
                if(isset($validateResponse['error'])){
                    $error[$id]=$validateResponse['error'];
                    continue;
                }
                else
                {
                    $uploadProductIds[]=$id;
                    $successXmlCreate++;
                    $image="";
                    $image=trim($productArray['image']);
                    $imageArr=[];
                    $imageArr=explode(',',$image);
                    if($productArray['type']=="simple")
                    {
                        $Catdata=[];
                        $Catdata = self::getCategoryArray($productArray['sku'],null,$productArray['category'],$productArray['walmart_attributes'],null,$productArray['vendor'],$productArray['type'],$connection);
                        $type=Jetproductinfo::checkUpcType($productArray['upc']);
                        $uploadType = 'MPItemUpdate';
                        if ($validateResponse['success'][$productArray['sku']] == false)
                        {
                            $uploadType = 'MPItem';
                        }
                        $productToUpload['MPItemFeed']['_value'][$key][$uploadType] =
                        [
                            'sku' => $productArray['sku'],
                            'Product' =>
                            [
                                'productName' => htmlspecialchars($productArray['title']),
                                'longDescription' =>
                                '<![CDATA[' . substr($productArray['description'], 0, 4999) . ']]>',
                                'shelfDescription' => '<![CDATA[' . $productArray['self_description'] . ']]>',
                                'shortDescription' => '<![CDATA[' . $productArray['short_description'] . ']]>',
                                'mainImage' => [
                                    'mainImageUrl' => $imageArr[0],
                                    'altText' => htmlspecialchars($productArray['title']),
                                ],
                                'productIdentifiers' =>
                                [
                                    'productIdentifier' => 
                                    [
                                        'productIdType' =>$type,
                                        'productId' =>$productArray['upc'],
                                    ]
                                ],
                                'productTaxCode' => $productArray['tax_code'],
                                $Catdata['category_id']=>$Catdata['attributes'],
                                //get category by parent and child 
                                /* $category['parent_cat_id'] => $this->attributes->setCategoryAttrData(
                                     $product,  $attributes, $category, $id),
                                 */
                            ],
                            'price' =>
                            [
                                'currency' => "USD",
                                'amount' => $productArray['price'],
                            ],
                            'shippingWeight' =>
                            [
                                'value' => $productArray['weight'],
                                'unit' => 'LB',
                            ],
                        ];
                        $key += 1;
                    }
                    else
                    {
                        $productVarArray = [];
                        $duplicateSkus = [];
                        $query='select jet.option_id,option_title,option_sku,wal.walmart_option_attributes,option_image,option_qty,option_price,option_weight,option_unique_id from `walmart_product_variants` wal INNER JOIN `jet_product_variants` jet ON jet.product_id=wal.product_id where wal.product_id="'.$id.'"';
                        $productVarArray= Data::sqlRecords($query,"all","select");
                        foreach($productVarArray as $value)
                        {
                            if(in_array($value['option_sku'],$duplicateSkus)){
                                continue;
                            }
                            else
                                $duplicateSkus[] = $value['option_sku'];
                            $Catdata=[];
                            $isParent=0;
                            if($value['option_sku']==$productArray['sku'])
                                $isParent=1;
                            $Catdata = self::getCategoryArray($productArray['sku'],$isParent,$productArray['category'],$productArray['walmart_attributes'],$value['walmart_option_attributes'],$productArray['vendor'],$productArray['type'],$connection);
                            
                            $type=Jetproductinfo::checkUpcType($value['option_unique_id']);
                            $uploadType = 'MPItemUpdate';
                            if ($validateResponse['success'][$value['option_sku']] == false)
                            {
                                $uploadType = 'MPItem';
                            }
                            $productToUpload['MPItemFeed']['_value'][$key][$uploadType] =
                            [
                                'sku' => $value['option_sku'],
                                'Product' =>
                                [
                                    'productName' => htmlspecialchars($productArray['title'].'~'.$value['option_title']),
                                    'longDescription' =>
                                    '<![CDATA[' . substr($productArray['description'], 0, 4999) . ']]>',
                                    'shelfDescription' => '<![CDATA[' . $productArray['self_description'] . ']]>',
                                    'shortDescription' => '<![CDATA[' . $productArray['short_description'] . ']]>',
                                    'mainImage' => [
                                        'mainImageUrl' => $imageArr[0],
                                        'altText' => htmlspecialchars($productArray['title']),
                                    ],
                                    'productIdentifiers' =>
                                    [
                                        'productIdentifier' =>
                                        [
                                            'productIdType' =>$type,
                                            'productId' =>$value['option_unique_id'],
                                        ]
                                    ],
                                    'productTaxCode' => $productArray['tax_code'],
                                    $Catdata['category_id']=>$Catdata['attributes'],
                                ],
                                'price' =>
                                [
                                    'currency' => "USD",
                                    'amount' => $productArray['price'],
                                ],
                                'shippingWeight' =>
                                [
                                    'value' => $productArray['weight'],
                                    'unit' => 'LB',
                                ],
                            ];
                            $key += 1;
                        }
                    }
                }
            }
            if($successXmlCreate>0)
            {
                //print_r($productToUpload);die;
                if(!file_exists(\Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID)){
            		mkdir(\Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID,0775, true);
       		    }
                $file=Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID.'/MPProduct-'.time().'.xml';
                $xml = new Generator();
                $xml->arrayToXml($productToUpload)->save($file);
                self::unEscapeData($file);
                $response = $this->postRequest(self::GET_FEEDS_ITEMS_SUB_URL, ['file' => $file]);
                $response = str_replace('ns2:', "", $response);
                //print_r($response);die;
                $responseArray=[];
                $responseArray=self::xmlToArray($response);
                if(isset($responseArray['FeedAcknowledgement']))
                {
                     $result =[];
                    $result = $this->getFeeds($responseArray['FeedAcknowledgement']['feedId']);
                    if(isset($results['results'][0],$results['results'][0]['itemsSucceeded']) && $results['results'][0]['itemsSucceeded']==1)
                    {
                        return ['uploadIds'=>$uploadProductIds,'feedId'=>$responseArray['FeedAcknowledgement']['feedId']];
                    }
                }
                elseif($responseArray['errors'])
                {
                    return $responseArray['errors'];
                }
            }
        }
        return false;
    }
    /**
     * validate product
     * @param [] $product
     * @return bool
     */    
    public static function validateProduct($product,$connection)
    {
        $price=$product['price'];
        $qty=$product['qty'];
        $errorArr=[];
        $validatedProduct=[];
        $validatedPro=[];
        if(!$product['short_description'] || strlen($product['short_description'])>1000)
        {
            $errorArr[]="shortDescription must be maximum of 1000 characters in length";
        }
        if(!$product['self_description'] || strlen($product['self_description'])>1000)
        {
            $errorArr[]="self_description must be maximum of 1000 characters in length";
        }
        if(!$product['description'] || strlen($product['description'])>4000)
        {
            $errorArr[]="description must be maximum of 4000 characters in length";
        }
        if(!$product['vendor'])
        {
            $errorArr[]="Missing brand";
        }
        if(!$product['tax_code'] || !is_numeric($product['tax_code'])){
            $errorArr[]="Missing product tax code";
        }
        if(!$product['category']){
            $errorArr[]="Missing category";
        }
        $image="";
        $image=trim($product['image']);
        $countImage=0;
        $imageArr=[];
        $ImageFlag=false;
        $imageArr=explode(',',$image);
        if($image!="" && count($imageArr)>0)
        {
            foreach ($imageArr as $value){
                if(self::checkRemoteFile($value)==false)
                    $countImage++;
            }
            if(count($imageArr)==$countImage)
                $ImageFlag=true;
        }
        if($image=='' || $ImageFlag){
            $errorArr[]="Missing or Invalid Image,";
        }
        //check if walmart attributes exist
        $isexistAttr=false;
        $isexistAttr = self::checkAttributes($product['category'],$connection);
        if($isexistAttr && !$product['walmart_attributes']){
            $errorArr[]="Missing walmart attributes";
        }
        $upc='';
        $upc=$product['upc'];
        if($product['type']=="simple")
        {
            if(($price<=0 || ($price && !is_numeric($price))) || trim($price)==""){
                $errorArr[]="Missing/invalid price";
            }
            if(($qty && !is_numeric($qty))||trim($qty)==""||($qty<=0 && is_numeric($qty))){
                $errorArr[]="Missing/invalid inventory";
            }
            $type="";
            $type=Jetproductinfo::checkUpcType($upc);
            $existUpc=false;
            if(!$type)
                $existUpc=Jetproductinfo::checkUpcSimple($upc,$product->id,$connection);
            if($product['upc']=="" || (strlen($product['upc'])>0 && $type=="") || (strlen($product['upc'])>0 && $existUpc))
            {
                $errorArr[]="Missing/invalid barcode";
            }
            $walmartHelper = new Walmartapi(API_USER,API_PASSWORD,CONSUMER_CHANNEL_TYPE_ID);
            $validatedPro[$product['sku']] = $walmartHelper->getItem($product['sku'], 'publishedStatus');
            if(is_array($errorArr) && count($errorArr)>0)
                $errorSkus[$product['sku']]=$errorArr;
        }
        else
        {
            $par_qty=0;
            $par_price="";
            $par_qty=trim($product['qty']);
            if($par_qty=="")
                $par_qty=0;
            $par_price=trim($product['price']);
            $c_par_price=false;
            $c_par_qty=false;
            if($par_price<=0 || (trim($par_price) && !is_numeric($par_price)) || trim($par_price)=="")
            {
                $c_par_price=false;
            }
            else
            {
                $c_par_price=true;
            }
            if((trim($par_qty)<=0 || !is_numeric($par_qty)))
            {
                $c_par_qty=false;
            }
            else
            {
                $c_par_qty=true;
            }
            //check if walmart attributes not available for category
            if(!$isexistAttr)
            {
                if(($price<=0 || ($price && !is_numeric($price))) || trim($price)==""){
                    $errorArr[]="Missing/invalid price";
                }
                if(($qty && !is_numeric($qty))||trim($qty)==""||($qty<=0 && is_numeric($qty))){
                    $errorArr[]="Missing/invalid inventory";
                }
               //product variant as simple
                $type="";
                $type=Jetproductinfo::checkUpcType($upc);
                if($type!="")
                    $existUpc=Jetproductinfo::checkUpcVariantSimple($product['upc'],$product['product_id'],$product['sku'],$connection);
                $validatedPro[$product['sku']] = $this->getItem($product['sku'], 'publishedStatus');
            }
            else
            {
                $productVarArray=[];
                $query='select option_id,option_sku,option_image,option_qty,option_price,option_unique_id from `jet_product_variants` where product_id="'.$product['product_id'].'"';
                $productVarArray = Data::sqlRecords($query,"all","select");
                foreach ($productVarArray as $pro)
                {
                    $upc="";
                    $price="";
                    $qty=0;
                    $opt_sku="";
                    $opt_sku=trim($pro['option_sku']);
                    $qty=trim($pro['option_qty']);
                    if($qty=="")
                        $qty=0;
                    $price=trim($pro['option_price']);
                    $upc = trim($pro['option_unique_id']);
                    if(!$c_par_qty && trim($qty)<=0){
                        $errorArr[]="Missing/invalid inventory for variants sku: ".$pro['option_sku'];
                    }
                    //check upc type
                    $type="";
                    $existUpc=false;
                    $type=Jetproductinfo::checkUpcType($upc);
                    $productasparent=0;
                    if($product['sku']==$pro['option_sku']){
                        $productasparent=1;
                    }
                    if($type!="")
                        $existUpc=Jetproductinfo::checkUpcVariants($upc,$product['product_id'],$pro['option_id'],$productasparent,$connection);
                    if($upc=="" || (strlen($upc)>0 && $type=="") || (strlen($upc)>0 && $existUpc))
                    {
                        $errorArr[]="Missing/invalid barcode for variants sku: ".$pro['option_sku'];
                    }
                    $walmartHelper = new Walmartapi(API_USER,API_PASSWORD,CONSUMER_CHANNEL_TYPE_ID);
                    $validatedPro[$pro['option_sku']] = $walmartHelper->getItem($pro['option_sku'], 'publishedStatus');
                }
            }
            
        }
        if(count($errorArr)>0){
            $validatedProduct['error']=$errorArr;
        }
        if(count($validatedPro)>0){
            $validatedProduct['success']=$validatedPro;
        }
        unset($imageArr);
        unset($connection);
        unset($validatedPro);
        return $validatedProduct;
    }
    /**
     * check required category attributes On Walmart
     * @param string|[] $category_id
     * @return bool
     */
    public static function checkAttributes($category_id='',$connection=array())
    {
        $catCollection=[];
        $query='select attributes from `walmart_category` where category_id="'.$category_id.'" LIMIT 1';
        $catCollection = Data::sqlRecords($query,"one","select");
        if(isset($catCollection['attributes']) && $catCollection['attributes'])
        {
            return true;
        }
        return false;
    }

    /**
     * get category attributes and parent id On Walmart
     * @param string|[] $category_id
     * @return bool
     */
    public static function getCategoryArray($sku=NULL,$isParent=NULL,$category_id=NULL,$mappedattributes=NULL,$mappedVarAttr=NULL,$brand=NULL,$type=NULL,$connection)
    {
        $catCollection=[];
        $attr_mapped=[];
        $data='';
        $query='select parent_id from `walmart_category` where category_id="'.$category_id.'"';
        $catCollection = Data::sqlRecords($query,"one","select");
        $attr_mapped=json_decode($mappedattributes,true);
        $attrArray=[];
        $mappedVar=[];
        if(is_array($catCollection) && count($catCollection)>0)
        {   
            //get walmart required attributes
            if(is_array($attr_mapped) && count($attr_mapped)>0)
            { 
                $attrArray=[];
                $parArray=[];
                $attrList=[];
                $isvar=false;
                $attr_mapped['brand']=[$brand];
                $attrValues=[];
                foreach ($attr_mapped as $attr_key=>$attr_value)
                {
                    if($type=="variants")
                    {
                        $isvar=true;
                        $mappedVar=json_decode($mappedVarAttr,true);
                        if($attr_key!='brand')
                        {
                            $attrValues[]=$attr_value[0];
                        }
                        if(isset($attr_value[0]) && array_key_exists($attr_value[0], $mappedVar))
                        {
                            $attrList[$attr_value[0]]=$mappedVar[$attr_value[0]];
                        }else{
                            $attrList[$attr_key]=$attr_value[0];
                        }
                    }
                    else
                    {
                        $attrList[$attr_key] = $attr_value[0];
                    }
                }
                if($isvar)
                {
                    $attrList['variantAttributeNames'] = $attrValues;
                }
                //send attribute values to create generate

                if(!is_null($catCollection['parent_id']) && $catCollection['parent_id']!=0)
                {
                    if(is_array(self::getCategoryOrder($catCollection['parent_id'])) && count(self::getCategoryOrder($catCollection['parent_id']))>0)
                    {
                        foreach(self::getCategoryOrder($catCollection['parent_id']) as $value)
                        {
                            $attributeArray=[];
                            $attributeArray = explode("/", $value);
                            //echo $attributeArray[0]." == ".$attr_key."<br>";
                            if(is_array($attributeArray) && count($attributeArray)>0 && array_key_exists($attributeArray[0], $attrList))
                            {
                                $parArray = array_merge_recursive($parArray, self::generateArray($attributeArray,$attrList[$attributeArray[0]],$isParent,$type,$sku));
                            }
                        }
                    }
                }
                if(is_array(self::getCategoryOrder($category_id)) && count(self::getCategoryOrder($category_id))>0)
                {
                    foreach (self::getCategoryOrder($category_id) as $value)
                    {
                        $attributeArray=[];
                        $attributeArray = explode("/", $value);
                        if(is_array($attributeArray) && count($attributeArray)>0 && array_key_exists($attributeArray[0], $attrList))
                        {
                            //var_Dump($attributeArray);echo "<hr>";
                            $attrArray = array_merge_recursive($attrArray, self::generateArray($attributeArray,$attrList[$attributeArray[0]],$isParent,$type,$sku));
                        }
                    }
                }
            }
            if(!is_null($catCollection['parent_id']) && $catCollection['parent_id']!=0)
            {  
                if(is_array($parArray) && count($parArray)>0)
                {
                    $parArray[$category_id]=$attrArray;
                    $data['category_id']=$catCollection['parent_id'];
                    $data['attributes']=$parArray;
                }
            }
            else
            {
                //if(is_array($attrArray) && count($attrArray)>0)
                $data['category_id']=$category_id;
                $data['attributes']=$attrArray;
            }
        } 
        return $data;   
    }
    public function generateArray($attributeArray = [], $value = null,$isParent=null,$type=null,$sku=null)
    {
        try 
        {
            $returnArray=[];
            if (count($attributeArray) == 1) 
            {
                $returnArray = [
                $attributeArray[0] => $value,
                ];
                return $returnArray;   
            }
            if (count($attributeArray) == 2) 
            {
                if (is_array($value)) 
                {
                    $returnArray[$attributeArray[0]]['_attribute'] = [];
                    foreach ($value as $key => $val) 
                    {
                        $returnArray[$attributeArray[0]]['_value'][$key][$attributeArray[1]] = $val;
                    }
                    //echo $type.'=='.$attributeArray[0]."<br>";
                    if($attributeArray[0]=='variantAttributeNames' && $type=='variants')
                    {
                        $returnArray['variantGroupId']=$sku;
                        if($isParent==1)
                            $returnArray['isPrimaryVariant']="true";
                    }
                }
                else
                {
                    $returnArray = [
                        $attributeArray[0] =>
                        [
                            $attributeArray[1] => $value,
                        ]
                    ];
                }
                return $returnArray;
            }
            if (count($attributeArray) == 3) 
            {
                $returnArray = [$attributeArray[0] => 
                [
                    $attributeArray[1] => 
                        [
                        $attributeArray[2] => $value,
                        ],
                    ]
                ];
                return $returnArray;
            }
            return false;
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    
    }
    public static function getCategoryOrder($category=null){
        $categoryOrder=[];
        switch($category){
            case 'FoodAndBeverage':{
                $categoryOrder=[
                    'isNutritionFactsLabelRequired', 'nutritionFactsLabel', 'nutritionFactsLabel',
                    'foodForm', 'isImitation', 'foodAllergenStatements/foodAllergenStatement', 'usdaInspected',
                    'vintage', 'timeAged/unit', 'timeAged/measure', 'variantAttributeNames/variantAttributeName',
                    'isGmoFree','variantGroupId', 'isPrimaryVariant', 'isBpaFree', 'isPotentiallyHazardousFood',
                    'isReadyToEat','caffeineDesignation', 'brand', 'manufacturer', 'spiceLevel', 'flavor', 'beefCut',
                    'poultryCut', 'color/colorValue', 'isMadeInHomeKitchen', 'nutrientContentClaims/nutrientContentClaim',
                    'safeHandlingInstructions', 'character/characterValue', 'occasion/occasionValue', 'isPersonalizable',
                    'fatCaloriesPerGram', 'recommendedUses/recommendedUse', 'carbohydrateCaloriesPerGram',
                    'totalProtein/unit', 'totalProtein/measure', 'totalProteinPercentageDailyValue/unit',
                    'totalProteinPercentageDailyValue/measure', 'proteinCaloriesPerGram', 'isFairTrade', 'isIndustrial',
                    'ingredients', 'releaseDate', 'servingSize', 'servingsPerContainer',
                    'organicCertifications/organicCertification', 'instructions', 'calories', 'caloriesFromFat/unit',
                    'caloriesFromFat/measure', 'totalFat/unit', 'totalFat/measure','totalFatPercentageDailyValue/unit',
                    'totalFatPercentageDailyValue/measure','totalCarbohydrate/unit','totalCarbohydrate/measure',
                    'totalCarbohydratePercentageDailyValue/unit','totalCarbohydratePercentageDailyValue/measure',
                    'nutrients/nutrient'
                ];
                break;
            }
            case 'AlcoholicBeverages':{
                $categoryOrder=[
                    'alcoholContentByVolume', 'alcoholProof', 'alcoholClassAndType', 'neutralSpiritsColoringAndFlavoring',
                    'whiskeyPercentage', 'isEstateBottled', 'wineAppellation', 'wineVarietal', 'containsSulfites', 'isNonGrape'
                ];
                break;
            }
            case 'HealthAndBeauty':{
                $categoryOrder=[
                    'swatchImages/swatchImage/swatchImageUrl', 'swatchImages/swatchImage/swatchVariantAttribute',
                    'collection','variantAttributeNames/variantAttributeName','flexibleSpendingAccountEligible',
                    'variantGroupId', 'isPrimaryVariant', 'fabricContent/fabricContentValue', 'isAdultProduct',
                    'fabricCareInstructions/fabricCareInstruction', 'brand', 'manufacturer', 'modelNumber',
                    'manufacturerPartNumber', 'gender', 'color/colorValue', 'ageGroup/ageGroupValue', 'isReusable',
                    'isDisposable', 'material/materialValue', 'isPowered', 'numberOfPieces', 'character/characterValue',
                    'powerType', 'isPersonalizable', 'bodyParts/bodyPart', 'isPortable', 'cleaningCareAndMaintenance',
                    'isSet', 'isTravelSize', 'recommendedUses', 'recommendedUses/recommendedUse', 'shape',
                    'compatibleBrands/compatibleBrand'
                ];
                break;
            }
            case 'MedicineAndSupplements':{
                $categoryOrder=[
                    'isDrugFactsLabelRequired', 'drugFactsLabel', 'isSupplementFactsLabelRequired', 'supplementFactsLabel',
                    'servingSize', 'servingsPerContainer', 'activeIngredients/activeIngredient/activeIngredientName',
                    'activeIngredients/activeIngredient/activeIngredientPercentage', 'inactiveIngredients/inactiveIngredient',
                    'healthConcerns/healthConcern','form','organicCertifications/organicCertification','instructions','dosage',
                    'stopUseIndications/stopUseIndication'
                ];
                break;
            }
            case 'PersonalCare':{
                $categoryOrder=[
                    'ingredientClaim/ingredientClaimValue', 'isLatexFree', 'absorbency', 'resultTime/unit',
                    'resultTime/measure', 'skinCareConcern','skinType','hairType','skinTone','spfValue','isAntiAging',
                    'isHypoallergenic','isOilFree','isParabenFree','isNoncomodegenic','scent','isUnscented','isVegan',
                    'isWaterproof','isTinted','isSelfTanning','isDrugFactsLabelRequired','drugFactsLabel',
                    'activeIngredients/activeIngredient/activeIngredientName',
                    'activeIngredients/activeIngredient/activeIngredientPercentage', 'inactiveIngredients/inactiveIngredient',
                    'form','organicCertifications/organicCertification','instructions',
                    'stopUseIndications/stopUseIndication'
                ];
                break;
            }
            case 'MedicalAids':{
                $categoryOrder=[
                    'isInflatable', 'isWheeled', 'isFoldable', 'isIndustrial','diameter/unit',
                    'diameter/measure', 'isAssemblyRequired','assemblyInstructions','maximumWeight/unit','maximumWeight/unit',
                    'isLatexFree','isAntiAging', 'isHypoallergenic','isOilFree','isParabenFree','isNoncomodegenic','scent',
                    'isUnscented','isVegan', 'isWaterproof','isWaterproof','healthConcerns/healthConcern'
                ];
                break;
            }
            case 'Optical':{
                $categoryOrder=[
                    'frameMaterial/frameMaterialValue', 'shape', 'eyewearFrameStyle', 'lensMaterial','eyewearFrameSize',
                    'uvRating', 'isPolarized','lensTint','isScratchResistant','hasAdaptiveLenses',
                    'lensType/lensTypeValue'
                ];
                break;
            }
            case 'HealthAndBeautyElectronics':{
                $categoryOrder=[
                    'batteriesRequired', 'batterySize', 'connections/connection', 'isCordless','hasAutomaticShutoff',
                    'screenSize/unit', 'screenSize/measure','displayTechnology'
                ];
                break;
            }
            case 'CarriersAndAccessories':{
                $categoryOrder=[
                    'swatchImages/swatchImage/swatchImageUrl', 'swatchImages/swatchImage/swatchVariantAttribute',
                    'variantAttributeNames/variantAttributeName','variantGroupId', 'isPrimaryVariant',
                    'fabricContent/fabricContentValue','isWeatherResistant', 'fabricCareInstructions/fabricCareInstruction',
                    'brand', 'dimensions', 'condition','isLined','manufacturer','numberOfWheels','modelNumber',
                    'handleMaterial/handleMaterialValue','manufacturerPartNumber','gender','color/colorValue','handleType',
                    'ageGroup/ageGroupValue','designer', 'leatherGrade','material/materialValue', 'pattern/patternValue',
                    'character/characterValue', 'monogramLetter','numberOfPieces', 'zipperMaterial', 'isPersonalizable',
                    'lockingMechanism','hardOrSoftCase', 'isMadeFromRecycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue/recycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue/percentageOfRecycledMaterial',
                    'isWheeled', 'isFairTrade', 'capacity','isWaterproof'
                ];
                break;
            }
            case 'CasesAndBags':{
                $categoryOrder=[
                    'finish', 'isReusable', 'occasion/occasionValue', 'recommendedUses/recommendedUse','bagStyle','isFoldable',
                    'fastenerType','numberOfCompartments','hasRemovableStrap','isTsaApproved','sport/sportValue',
                    'maximumWeight/unit','maximumWeight/value','shape','screenSize/unit','screenSize/value',
                    'compatibleBrands/compatibleBrand','compatibleDevices/compatibleDevice'
                ];
                break;
            }
            case 'Jewelry':{
                $categoryOrder=[
                    'size','jewelryStyle','metal','plating','swatchImages/swatchImage/swatchImageUrl',
                    'swatchImages/swatchImage/swatchVariantAttribute','karats','gemstone',
                    'variantAttributeNames/variantAttributeName','birthstone','variantGroupId','gemstoneShape',
                    'isPrimaryVariant','carats/unit','carats/value','diamondClarity','gemstoneCut','chainLength/unit',
                    'chainLength/measure','brand', 'manufacturer', 'modelNumber',
                    'manufacturerPartNumber','gender','color/colorValue','ageGroup/ageGroupValue',
                    'material/materialValue', 'pattern/patternValue',
                    'character/characterValue', 'occasion/occasionValue',
                    'isPersonalizable', 'bodyParts', 'isPersonalizable',
                    'bodyParts/bodyPart','recycledMaterialContent/recycledMaterialContentValue/recycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue/percentageOfRecycledMaterial',
    
                ];
                break;
            }
            case 'Rings':{
                $categoryOrder=[
                    'ringStyle/ringStyleValue'
                ];
                break;
            }
            case 'Office':{
                $categoryOrder=[
                    'inkColor/inkColorValue','numberOfSheets','isRefillable','swatchImages/swatchImage/swatchImageUrl',
                    'swatchImages/swatchImage/swatchVariantAttribute','systemOfMeasurement',
                    'variantAttributeNames/variantAttributeName','variantGroupId','isAntiglare',
                    'isPrimaryVariant','fabricContent/fabricContentValue','finish','isRecyclable','isMagnetic','brand',
                    'envelopeSize','condition','holeSize/unit','holeSize/unit','manufacturer','theme/themeValue', 'paperSize',
                    'year','modelNumber','manufacturerPartNumber','calendarFormat/unit','calendarTerm/value',
                    'color/colorValue','batteriesRequired','ageGroup/ageGroupValue','dexterity','batterySize',
                    'material/materialValue', 'pattern/patternValue','isPowered',
                    'character/characterValue','powerType', 'occasion/occasionValue','isMadeFromRecycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue/recycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue/percentageOfRecycledMaterial',
                    'recommendedUses/recommendedUse','isRetractable','isIndustrial','isTearResistant','capacity',
                    'brightness/unit','brightness/measure','shape','compatibleDevices/compatibleDevice'
                ];
                break;
            }
            case 'Other':{
                $categoryOrder=[
                    'swatchImages/swatchImage/swatchImageUrl','swatchImages/swatchImage/swatchVariantAttribute',
                    'systemOfMeasurement', 'variantAttributeNames/variantAttributeName','variantGroupId','isPrimaryVariant',
                    'fabricContent/fabricContentValue','finish', 'fabricCareInstructions/fabricCareInstruction','brand',
                    'manufacturer','modelNumber','manufacturerPartNumber','gender','color/colorValue',
                    'recommendedRooms/recommendedRoom','connections/connection','material/materialValue',
                    'pattern/patternValue','isPowered','character/characterValue','powerType', 'isPortable',
                    'recommendedLocations/recommendedLocation','isRetractable','isFoldable','isCollectible','isIndustrial',
                    'isAssemblyRequired','assemblyInstructions','recommendedSurfaces/recommendedSurface','volts/unit',
                    'volts/measure','shape','displayTechnology'
                ];
                break;
            }
            case 'Storage':{
                $categoryOrder=[
                    'collection', 'shelfDepth/unit', 'shelfDepth/measure', 'shelfStyle','recommendedUses/recommendedUse',
                    'drawerPosition', 'drawerDimensions','numberOfDrawers','numberOfShelves','maximumWeight/unit',
                    'maximumWeight/measure','capacity'
                ];
                break;
            }
            case 'CleaningAndChemical':{
                $categoryOrder=[
                    'isRecyclable', 'isBiodegradable', 'isEnergyStarCertified', 'isCombustible','isMadeFromRecycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue', 'isFlammable','ingredients','handleLength/unit',
                    'handleLength/measure','fluidOunces/unit','fluidOunces/measure','scent',
                    'activeIngredients/activeIngredient/activeIngredientName',
                    'activeIngredients/activeIngredient/activeIngredientPercentage', 'inactiveIngredients/inactiveIngredient',
                    'form','instructions'
                ];
                break;
            }
            case 'Photography':{
                $categoryOrder=[
                    'swatchImages/swatchImage/swatchImageUrl',
                    'swatchImages/swatchImage/swatchVariantAttribute','accessoriesIncluded/accessoriesIncludedValue',
                    'variantAttributeNames/variantAttributeName','variantGroupId','isPrimaryVariant','isWeatherResistant',
                    'hasSignalBooster','hasWirelessMicrophone','brand','manufacturer','modelNumber',
                    'manufacturerPartNumber','gender','color/colorValue','batteriesRequired','batterySize',
                    'memoryCardType/memoryCardTypeValue','connections/connection','material/materialValue','numberOfPieces',
                    'isPortable','cleaningCareAndMaintenance','recommendedLocations/recommendedLocation',
                     'isAssemblyRequired','assemblyInstructions','isWaterproof',
                    'hasTouchscreen','recordableMediaFormats/recordableMediaFormat','compatibleBrands/compatibleBrand',
                    'compatibleDevices/compatibleDevice','wirelessTechnologies/wirelessTechnology'
                ];
                break;
            }
            
            case 'PhotoAccessories':{
                $categoryOrder=[
                    'fabricContent/fabricContentValue', 'condition', 'pattern/patternValue', 'isRemoteControlIncluded',
                    'isMadeFromRecycledMaterial', 'occasion/occasionValue', 'hardOrSoftCase','isCordless','lightOutput/unit',
                    'lightOutput/measure','maximumWeight/unit','maximumWeight/measure','capacity', 'volts/unit','volts/measure',
                    'watts/unit','watts/measure','shape', 'inputsAndOutputs/inputsAndOutput/inputOutputType',
                    'inputsAndOutputs/inputsAndOutput/inputOutputQuantity','displayTechnology','hasBluetooth','lightBulbType',
                    'wirelessTechnologies/wirelessTechnologie'
                ];
                break;
            }
            case 'CamerasAndLenses':{
                $categoryOrder=[
                    'ageGroup/ageGroupValue', 'powerType', 'diameter/unit', 'diameter/measure','numberOfMegapixels/unit',
                    'numberOfMegapixels/measure','focalLength/measure','focalLength/unit', 'hasShoulderStrap','hasHandle',
                    'magnification','fieldOfView','isFogResistant','lensDiameter/unit','lensDiameter/measure','isMulticoated',
                    'shootingPrograms','shootingMode','opticalZoom','selfTimerDelay/unit','selfTimerDelay/measure',
                    'hasSelfTimer','hasRemovableFlash','digitalZoom','focusType/focusTypeValue','hasRedEyeReduction',
                    'minimumShutterSpeed/unit','minimumShutterSpeed/unit','lockType','maximumShutterSpeed/unit',
                    'maximumShutterSpeed/measure','sensorResolution/unit','sensorResolution/measure','maximumShootingSpeed',
                    'minimumAperture','hasDovetailBarSystem','hasLcdScreen','maximumAperture','hasMemoryCardSlot',
                    'microphoneIncluded','hasNightVision','lensFilterType','isParfocal','flashType','filmCameraType',
                    'attachmentStyle','exposureModes/exposureMode','cameraLensType','displayResolution/unit',
                    'displayResolution/measure','focalRatio','lensCoating','operatingTemperature/unit',
                    'operatingTemperature/measure','isLockable','lensType/lensTypeValue','screenSize/unit','screenSize/measure',
                    'displayTechnology','hasFlash','standbyTime/unit','standbyTime/measure',
                    'activeIngredients/activeIngredient/activeIngredientPercentage', 'inactiveIngredients/inactiveIngredient',
                    'form','instructions'
                ];
                break;
            }
            case 'ToolsAndHardware':{
                $categoryOrder=[
                    'swatchImages/swatchImage/swatchImageUrl',
                    'swatchImages/swatchImage/swatchVariantAttribute','accessoriesIncluded/accessoriesIncludedValue',
                    'variantGroupId','variantAttributeNames/variantAttributeName','isPrimaryVariant','isWeatherResistant',
                    'isFireResistant','brand','manufacturer','color/colorValue','material/materialValue','numberOfPieces',
                    'cleaningCareAndMaintenance','recommendedUses/recommendedUse',
                    'isIndustrial','isWaterproof','shape'
                ];
                break;
            }
            case 'Clothing':{
                $categoryOrder=[
                    'swatchImages/swatchImage/swatchImageUrl',
                    'swatchImages/swatchImage/swatchVariantAttribute', 'variantGroupId',
                    'variantAttributeNames/variantAttributeName','isPrimaryVariant',
                    'fabricContent/fabricContentValue/materialName', 'fabricCareInstructions/fabricCareInstruction','brand',
                    'manufacturer', 'modelNumber', 'clothingSize', 'gender','color/colorValue', 'ageGroup/ageGroupValue',
                    'clothingSizeType', 'isMaternity', 'pattern/patternValue', 'character/characterValue',
                    'occasion/occasionValue', 'apparelCategory', 'isPersonalizable', 'isMadeFromRecycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue/recycledMaterial',
                    'recycledMaterialContent/recycledMaterialContentValue/percentageOfRecycledMaterial',
                    'gotsCertification', 'season/seasonValue', 'sport/sportValue'
                ];
                break;
            }
            case 'ShirtsAndTops':{
                $categoryOrder=[
                    'shirtSize', 'shirtNeckStyle', 'sleeveStyle'
                ];
                break;
            }
            case 'WomensSwimsuits':{
                $categoryOrder=[
                    'braSize', 'swimsuitStyle'
                ];
                break;
            }
            case 'Bras':{
                $categoryOrder=[
                    'braSize', 'swimsuitStyle'
                ];
                break;
            }
            case 'Skirts':{
                $categoryOrder=[
                    'waistSize/unit','waistSize/measure', 'skirtAndDressCut'
                ];
                break;
            }
            case 'PantsAndShorts':{
                $categoryOrder=[
                    'pantSize/inseam','pantSize/waistSize','pantRise', 'pantStyle', 'pantPanelStyle'
                ];
                break;
            }
            case 'ClothingAccessories':{
                $categoryOrder=[
                    'material/materialValue'
                ];
                break;
            }
            case 'Dresses':{
                $categoryOrder=[
                    'skirtAndDressCut','dressStyle','sleeveStyle'
                ];
                break;
            }
            case 'DressShirts':{
                $categoryOrder=[
                    'dressShirtSize/neckSize', 'dressShirtSize/sleeveLength','collarType','sleeveStyle'
                ];
                break;
            }
            case 'Socks':{
                $categoryOrder=[
                    'sockSize', 'sockStyle'
                ];
                break;
            }
            case 'Panties':{
                $categoryOrder=[
                    'pantySize', 'pantyStyle'
                ];
                break;
            }
        }
        return $categoryOrder;
    }
    /**
     * Update Inventory On Walmart
     * @param string|[] $ids
     * @return bool
     */
    public function updateInventoryOnWalmart($product)
    {
        $inventoryArray = [
            'InventoryFeed' => [
                '_attribute' => [
                    'xmlns' => "http://walmart.com/",
                ],
                '_value' => [
                    0 => ['InventoryHeader' => [
                        'version' => '1.4',
                    ],
                    ],
                ]
            ]
        ];
        $timeStamp = (string)time();
        $isInvFeed=0;
        if (is_array($product) && count($product)>0) 
        {
            $key = 0;
            foreach($product as $pro)
            {
                //check product available on walmart
                $response = $this->getItem($pro['sku']);
                if(is_array($response) && count($response)){
                    self::saveStatus($pro['id'],$response);
                }
                $isInvFeed++;
                if ($pro['type'] == 'variants') 
                {
                    $varProducts=[];
                    $query="select option_sku,option_qty from `jet_product_variants` where product_id='".$pro['id']."'";
                    $varProducts = Data::sqlRecords($query,"all","select");
                    foreach ($varProducts as $value) 
                    {
                        $key += 1;
                        $inventoryArray['InventoryFeed']['_value'][$key] = [
                            'inventory' => [
                                'sku' => $value['option_sku'],
                                'quantity' => [
                                    'unit' => 'EACH',
                                    'amount' =>  $value['option_qty'],
                                ],
                                'fulfillmentLagTime' => '1',
                            ]
                        ];
                    }
                } 
                else 
                {
                    $key += 1;
                    $inventoryArray['InventoryFeed']['_value'][$key] = [
                        'inventory' => [
                            'sku' =>  $pro['sku'],
                            'quantity' => [
                                'unit' => 'EACH',
                                'amount' =>  $pro['qty'],
                            ],
                            'fulfillmentLagTime' => '1',
                        ]
                    ];
                }
            }    
        }
        if($isInvFeed>0)
        {
            if(!file_exists(\Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID.'/inventory')){
                mkdir(\Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID.'/inventory',0775, true);
            }
            $file=Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID.'/inventory/MPProduct-'.time().'.xml';
            $xml=new Generator();
            $xml->arrayToXml($inventoryArray)->save($file);
            $response = $this->postRequest(self::GET_FEEDS_INVENTORY_SUB_URL, ['file' => $file]);
            $responseArray =  self::xmlToArray($response);
            if(isset($responseArray['FeedAcknowledgement']))
            {
                $result=[];
                $result = $this->getFeeds($responseArray['FeedAcknowledgement']['feedId']);
                if(isset($results['results'][0],$results['results'][0]['itemsSucceeded']) && $results['results'][0]['itemsSucceeded']==1)
                {
                    return ['feedId'=>$responseArray['FeedAcknowledgement']['feedId']];
                }
                //return ['feedId'=>$responseArray['FeedAcknowledgement']['feedId']];
            }
            elseif(isset($responseArray['errors']))
            {
                return ['errors'=>$responseArray['errors']];
            }
            //return $responseArray;
        }
    }
    
    /**
     * Update Price On Walmart
     * @param string|[] $ids
     * @return bool
     */
    public function updatePriceOnWalmart($product = [])
    {
        $timeStamp = (string)time();
        $priceArray = [
            'PriceFeed' => [
                '_attribute' => [
                    'xmlns:gmp' => "http://walmart.com/",
                ],
                '_value' => [
                    0 => [
                        'PriceHeader' => [
                            'version' => '1.5',
                        ],
                    ],
                ]
            ]
        ];
        $isPriceFeed=0;
        if (is_array($product) && count($product)>0) 
        {
            $key = 0;
            foreach($product as $pro)
            {
                $isPriceFeed++;
                if ($pro['type'] == 'variants') 
                {
                    $varProducts=[];
                    $query="select option_sku,option_price from `jet_product_variants` where product_id='".$pro['id']."'";
                    $varProducts = Data::sqlRecords($query,"all","select");
                    foreach ($varProducts as $value) 
                    {
                        $key += 1;
                        $priceArray['PriceFeed']['_value'][$key] = [
                            'Price' => [
                                'itemIdentifier' => [
                                    'sku' => $value['option_sku']
                                ],
                                'pricingList' => [
                                    'pricing' => [
                                        'currentPrice' => [
                                            'value' => [
                                                '_attribute' => [
                                                    'currency' => 'USD',
                                                    'amount' => $value['option_price']
                                                ],
                                                '_value' => [

                                                ]
                                            ]
                                        ],
                                        'currentPriceType' => 'BASE',
                                        'comparisonPrice' => [
                                            'value' => [
                                                '_attribute' => [
                                                    'currency' => 'USD',
                                                    'amount' => $value['option_price']
                                                ],
                                                '_value' => [

                                                ]
                                            ]
                                        ],
                                    ]
                                ]
                            ]
                        ];
                    }
                } 
                else 
                {
                    $key += 1;
                    $priceArray['PriceFeed']['_value'][$key] = [
                        'Price' => [
                            'itemIdentifier' => [
                                'sku' => $pro['sku']
                            ],
                            'pricingList' => [
                                'pricing' => [
                                    'currentPrice' => [
                                        'value' => [
                                            '_attribute' => [
                                                'currency' => 'USD',
                                                'amount' => $pro['price']
                                            ],
                                            '_value' => [

                                            ]
                                        ]
                                    ],
                                    'currentPriceType' => 'BASE',
                                    'comparisonPrice' => [
                                        'value' => [
                                            '_attribute' => [
                                                'currency' => 'USD',
                                                'amount' => $pro['price']
                                            ],
                                            '_value' => [

                                            ]
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ];
                }
            }    
        }
        if($isPriceFeed>0)
        {
            if(!file_exists(\Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID.'/price')){
                mkdir(\Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID.'/price',0775, true);
            }
            $file=Yii::getAlias('@webroot').'/var/product/xml/'.MERCHANT_ID.'/price/MPProduct-'.time().'.xml';
            $xml=new Generator();
            //var_dump($priceArray);die;
            $xml->arrayToXml($priceArray)->save($file);
            $response = $this->postRequest(self::GET_FEEDS_PRICE_SUB_URL, ['file' => $file]);
            $responseArray =  self::xmlToArray($response);
            if(isset($responseArray['FeedAcknowledgement']))
            {
                $result = $this->getFeeds($responseArray['FeedAcknowledgement']['feedId']);
                if(isset($results['results'][0],$results['results'][0]['itemsSucceeded']) && $results['results'][0]['itemsSucceeded']==1)
                {
                    return ['feedId'=>$responseArray['FeedAcknowledgement']['feedId']];
                }
                //return ['feedId'=>$responseArray['FeedAcknowledgement']['feedId']];
            }
            elseif($responseArray['errors'])
            {
                return ['errors'=>$responseArray['errors']];
            }
            //return $responseArray;
        }
    }
    
    public static function checkRemoteFile($url)
    {
        stream_context_set_default( [
            'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            ],
        ]);
        $headers = get_headers($url);
        if(substr($headers[0], 9, 3) == '200') {
            return true;
        }else{
            return false;
        }
    }
    
    //delete the element
    public function deleteRequest($url, $params = [])
    {
        $signature = $this->apiSignature->getSignature($url, 'DELETE');
        $url = $this->apiUrl . $url;
    
        $headers = [];
        $headers[] = "WM_SVC.NAME: Walmart Marketplace";
        $headers[] = "WM_QOS.CORRELATION_ID: " . base64_encode(\phpseclib\Crypt\Random::string(16));
        $headers[] = "WM_SEC.TIMESTAMP: " . $this->apiSignature->timestamp;
        $headers[] = "WM_SEC.AUTH_SIGNATURE: " . $signature;
        $headers[] = "WM_CONSUMER.ID: " .  $this->apiConsumerId;
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/xml";
        if (isset($params['headers']) && !empty($params['headers'])) {
            $headers[] = $params['headers'];
        }
        $headers[] = "HOST: marketplace.walmartapis.com";
    
        //echo $url;
        //print_r($headers);die;
    
        //for disabling curl ssl verifications
        //$this->resource->setConfig(['verifypeer' => false]);
        //$this->resource->setConfig(['verifyhost' => false]);
    
        //turning off header from curl response
        $this->resource->setConfig(['header' => 0]);
    
        /*
         * for curl https use install certificate
        * install sudo apt-get install ca-certificates
        * add to setOptions(): CURLOPT_CAINFO => '/etc/ssl/certs/ca-certificates.crt'
        * or add to add certificate location to php.ini:
        * "[curl];A default value for the CURLOPT_CAINFO option. This is required to be an; absolute path.
        curl.cainfo = curl-ca-bundle.crt"
        * links:  https://curl.haxx.se/ca/cacert.pem
        * http://aerendir.me/2016/04/29/magento-2-ssl-certificate-problem-unable-to-get-local-issuer-certificate-curl-problem/
        * http://stackoverflow.com/questions/3160909/how-do-i-deal-with-certificates-using-curl-while-trying-to-access-an-https-url/
        *
        */
        $this->resource->setOptions([CURLOPT_HEADER => 1, CURLOPT_RETURNTRANSFER=>'true' ]);
        //for curl https use install certificate, add certificate location to php.ini
        $this->resource->setOptions([
            CURLOPT_HEADER => 1, CURLOPT_RETURNTRANSFER=>'true',
            CURLOPT_CUSTOMREQUEST => "DELETE"
            ]);
        $this->resource->write("DELETE", $url, '1.1', $headers);
        // CURLOPT_CAINFO => '/etc/ssl/certs/ca-certificates.crt'
        $serverOutput = $this->resource->read();
        $this->resource->close();
        //print_r($this->resource->getError());die;
        if (!$serverOutput) {
            $this->_logger->debug('Api Not Working ! Try after Sometime');
            return false;
        }
        return $serverOutput;
    }
    
    //acknowledge order
    public function acknowledgeOrder($purchaseOrderId , $subUrl = self::GET_ORDERS_SUB_URL)
    {
        $response = $this->postRequest($subUrl.'/'.$purchaseOrderId.'/acknowledge',
            [
            'headers' => 'WM_CONSUMER.CHANNEL.TYPE: ' . $this->apiConsumerChannelId,
            ]
        );
        try {
            
            return json_decode((string)$response,true);
        }
        catch(\Exception $e){
           echo $e->getMessage();die;
            return false;
        }
    
    }
    
    //ship order
    public function shipOrder($postData = null , $subUrl = self::GET_ORDERS_SUB_URL)
    {
        $purchaseOrderId = $postData['shipments'][0]['purchaseOrderId'];
        $shipArray = [
        'ns2:orderShipment' => [
        '_attribute' => [
        'xmlns:ns2' => "http://walmart.com/mp/v3/orders",
        'xmlns:ns3' => "http://walmart.com/",
        ],
        '_value' => [
        'ns2:orderLines' => [
        '_attribute' => [
        ],
        '_value' => [
    
        ]
        ],
        ]
        ]
        ];
        $url = 'www.fedex.com';
        if (isset($postData['shipments'][0]['shipment_tracking_url'])) {
            $url = $postData['shipments'][0]['shipment_tracking_url'];
        }
        foreach ($postData['shipments'] as $key => $values) {
            if (!isset($values['shipment_items'])) {
                continue;
            }
            foreach ($values['shipment_items'] as $value) {
                $lineNumbers =  explode(',', $value['lineNumber']);
                foreach ($lineNumbers as $lineNumber) {
                    $shipArray['ns2:orderShipment'][ '_value']['ns2:orderLines']['_value'][$key] =
                    ['ns2:orderLine' => [
                    'ns2:lineNumber' => $lineNumber,
                    'ns2:orderLineStatuses' => [
                    'ns2:orderLineStatus' => [
                    'ns2:status' => 'Shipped',
                    'ns2:statusQuantity' => [
                    'ns2:unitOfMeasurement' => 'Each',
                    'ns2:amount' => '1'//(string)$value['response_shipment_sku_quantity']
                    ],
                    'ns2:trackingInfo' => [
                    'ns2:shipDateTime' => $postData['shipments'][0]['carrier_pick_up_date'],
                    'ns2:carrierName' => [
                    'ns2:carrier' => $postData['shipments'][0]['carrier']
                    ],
                    'ns2:methodCode' => $postData['shipments'][0]['methodCode'],
                    'ns2:trackingNumber' => $postData['shipments'][0]['shipment_tracking_number'],
                    'ns2:trackingURL' => $url
                    ]
                    ]
                    ]
                    ]
                    ];
                }
            }
    
        }
        $customGenerator = $this->objectManager->create('Ced\Walmart\Helper\Custom\Generator');
        $customGenerator->arrayToXml($shipArray);
        $str = preg_replace
        ('/(\<\?xml\ version\=\"1\.0\"\?\>)/', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
            $customGenerator->__toString());
        $params['data'] = $str;
        $this->createFile($str, ['type' => 'string', 'name' => 'OrderShip']);
        $params['headers'] = 'WM_CONSUMER.CHANNEL.TYPE: ' . $this->apiConsumerChannelId;
        $response = $this->postRequest($subUrl.'/'.$purchaseOrderId.'/shipping',
            $params);
        try{
            $parser = $this->objectManager->create('\Magento\Framework\Xml\Parser');
            $response = str_replace('ns:2', '', $response);
            $data = $parser->loadXML($response)->xmlToArray();
            return $this->json->jsonEncode($data);
        }
        catch(\Exception $e){
            $this->_logger->debug('Walmart : shipOrder : Response: '.$response);
            return false;
        }
    }
    
    /**
     * Reject Order
     * @param string $purchaseOrderId
     * @param string $dataship
     * @param string $subUrl
     * @return string
     * @link  https://developer.walmartapis.com/#cancelling-order-lines
     */
    
    public function rejectOrder($purchaseOrderId , $dataship , $subUrl = self::GET_ORDERS_SUB_URL)
    {
        $cancelArray = [
        'ns2:orderCancellation' => [
        '_attribute' => [
        'xmlns:ns2' => "http://walmart.com/mp/v3/orders",
        'xmlns:ns3' => "http://walmart.com/",
        ],
        '_value' => [
        'ns2:orderLines' => []
        ]
        ]
        ];
    
        $counter = 0;
        foreach ($dataship['shipments'] as $values)
        {
            if (!isset($values['cancel_items'])) {
                echo 'shit';
                continue;
            }
            foreach ($values['cancel_items'] as $value) 
            {
                $lineNumbers =  explode(',', $value['lineNumber']);
                $cancelArray['ns2:orderCancellation']['_value']['ns2:orderLines']['_attribute'] = [];
                foreach ($lineNumbers as $lineNumber) 
                {
                    $cancelArray['ns2:orderCancellation']['_value']['ns2:orderLines']
                    ['_value'][$counter]['ns2:orderLine']['ns2:lineNumber'] = (string)$lineNumber;
                    $cancelArray['ns2:orderCancellation']['_value']['ns2:orderLines']
                    ['_value'][$counter]['ns2:orderLine']['ns2:orderLineStatuses'] = [
                    'ns2:orderLineStatus' => [
                    'ns2:status' => 'Cancelled',
                    'ns2:cancellationReason' => 'CANCEL_BY_SELLER',
                    'ns2:statusQuantity' => [
                    'ns2:unitOfMeasurement' => 'EACH',
                    'ns2:amount' => '1'
                        ]
                        ]
                        ];
                    $counter++;
                }
            }
    
        }
        $customGenerator = $this->objectManager->create('Ced\Walmart\Helper\Custom\Generator');
        $customGenerator->arrayToXml($cancelArray);
        $str = preg_replace('/(\<\?xml\ version\=\"1\.0\"\?\>)/', '<?xml version="1.0" encoding="UTF-8" ?>',
            $customGenerator->__toString());
        $params['data'] = $str;
        $params['headers'] = 'WM_CONSUMER.CHANNEL.TYPE: ' . $this->apiConsumerChannelId;
        $this->createFile($str, ['type' => 'string', 'name' => 'CancelOrder']);
        $response = $this->postRequest($subUrl.'/'.$purchaseOrderId.'/cancel',
            $params);
        try{
            return $this->json->jsonDecode($response);
        }
        catch(\Exception $e){
            $this->_logger->debug('Reject Order : NO JSON Response . Response Was :- '.$response);
            return false;
        }
    }
    
    /**
     * Refund Order
     * @param string $purchaseOrderId
     * @param string $orderData
     * @param string $subUrl
     * @return string
     * @link  https://developer.walmartapis.com/#cancelling-order-lines
     */
    public function refundOrder($purchaseOrderId , $orderData , $subUrl = self::GET_ORDERS_SUB_URL)
    {
        $refundData = [
        'ns2:orderRefund' => [
        '_attribute' => [
        'xmlns:ns2' => "http://walmart.com/mp/v3/orders",
        'xmlns:ns3' => "http://walmart.com/",
        ],
        '_value' => [
        'ns2:purchaseOrderId' => $purchaseOrderId,
        'ns2:orderLines' => [
        'ns2:orderLine' =>[
        'ns2:lineNumber' => $orderData['lineNumber'],
        'ns2:refunds' => [
        'ns2:refund' => [
        'ns2:refundComments' => $orderData['refundComments'],
        'ns2:refundCharges' => [
        '_attribute' => [],
        '_value' =>[
            0 => [
            'ns2:refundCharge' => [
            'ns2:refundReason' => $orderData['refundReason'],
            'ns2:charge' => [
            'ns2:chargeType' => 'Product',
            'ns2:chargeName' => 'Item Price',
            'ns2:chargeAmount' => [
            'ns2:currency' => 'USD',
            'ns2:amount' => $orderData['amount']
                ],
                'ns2:tax' => [
                'ns2:taxName' => 'Item Price Tax',
                'ns2:taxAmount' => [
                'ns2:currency' => 'USD',
                'ns2:amount' => $orderData['taxAmount']
                ]
                ]
                ]
                    ]
                    ],
                    1 =>[
                    'ns2:refundCharge' => [
                        'ns2:refundReason' => $orderData['refunReasonShipping'],
                            'ns2:charge' => [
                            'ns2:chargeType' => 'Product',
                            'ns2:chargeName' => 'Item Price',
                            'ns2:chargeAmount' => [
                            'ns2:currency' => 'USD',
        'ns2:amount' => $orderData['shipping']
        ],
        'ns2:tax' => [
        'ns2:taxName' => 'Item Price Tax',
        'ns2:taxAmount' => [
        'ns2:currency' => 'USD',
            'ns2:amount' => $orderData['shippingTax']
            ]
            ]
                ]
                ]
                ]
                ]
    
                ]
                ]
                    ]
                        ]
                        ],
                        ]
                        ]
                        ];
                        $customGenerator = $this->objectManager->create('Ced\Walmart\Helper\Custom\Generator');
                        $customGenerator->arrayToXml($refundData);
                        $str = preg_replace('/(\<\?xml\ version\=\"1\.0\"\?\>)/', '<?xml version="1.0" encoding="UTF-8" ?>',
                            $customGenerator->__toString());
                            $params['data'] = $str;
                            $params['headers'] = 'WM_CONSUMER.CHANNEL.TYPE: ' . $this->apiConsumerChannelId;
                            $this->createFile($str, ['type' => 'string', 'name' => 'RefundOrder']);
                            $response = $this->postRequest($subUrl.'/'.$purchaseOrderId.'/refund',
                                $params);
        try{
            return $this->json->jsonDecode($response);
    }
    catch(\Exception $e){
    $this->_logger->debug('Refund Order : NO JSON Response . Response Was :- '.$response);
        return false;
    }
    }

    /**
     * @return array
     */
    public function refundreasonOptionArr()
    {
        return [
        [
        'value' => '', 'label' => __('Please Select an Option')
        ],
        [
        'value' => 'BillingError', 'label' =>  __('BillingError')
        ],
        [
        'value' => 'TaxExemptCustomer', 'label' =>  __('TaxExemptCustomer')
        ],
        [
        'value' => 'ItemNotAsAdvertised', 'label' =>  __('ItemNotAsAdvertised')
        ],
        [
        'value' =>'IncorrectItemReceived', 'label' =>  __('IncorrectItemReceived')
        ],
        [
        'value' => 'CancelledYetShipped', 'label' =>  __('CancelledYetShipped')
        ],
        [
        'value' => 'ItemNotReceivedByCustomer', 'label' =>  __('ItemNotReceivedByCustomer')
        ],
        [
        'value' => 'IncorrectShippingPrice', 'label' =>  __('IncorrectShippingPrice')
        ],
        [
        'value' => 'DamagedItem', 'label' =>  __('DamagedItem')
        ],
        [
        'value' => 'DefectiveItem', 'label' =>  __('DefectiveItem')
        ],
        [
        'value' => 'CustomerChangedMind', 'label' =>  __('CustomerChangedMind')
        ],
        [
        'value' => 'CustomerReceivedItemLate', 'label' =>  __('CustomerReceivedItemLate')
        ],
        [
        'value' => 'Missing Parts / Instructions', 'label' =>  __('Missing Parts / Instructions')
        ],
        [
        'value' => 'Finance -> Goodwill', 'label' =>  __('Finance -> Goodwill')
        ],
        [
        'value' => 'Finance -> Rollback', 'label' =>  __('Finance -> Rollback')
        ]
        ];
    }
    
    /**
    *
    * convert xml response to array
    * @param $xml
    */
    public static function xmlToArray($xml){
        $parser=new Parser();
        $data = $parser->loadXML($xml)->xmlToArray();
        return $data;
    }
    
    /**
     * save product status(s) for uploaded
     * products of products
     * @param Response
     * @param id
     */
    public static function saveStatus($id,$response)
    {
        if(is_array($response) && count($response)>0 && isset($response['MPItemView'][0],$response['MPItemView'][0]['publishedStatus'])){
            //update product status
            $query="update `walmart_product` set status='".$response['MPItemView'][0]['publishedStatus']."' where product_id='".$id."'";
            Data::sqlRecords($query,null,"update");
        }
    }
    public static function deleteFeed($feedId)
    {
        if($query)
        {
            $query="delete from `walmart_product_feed` where feedId='".$feedId."'";
            Data::sqlRecords($query,null,"delete");
        }
    }
    public static function is_json($string) 
    {
        try
        {
            $data = json_decode($string); 
            return (json_last_error() == JSON_ERROR_NONE) ? : FALSE;   
        }
        catch(Exception $e)
        {
            return false;
        }
    }

}
