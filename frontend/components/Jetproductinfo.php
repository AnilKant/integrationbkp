<?php 
namespace frontend\components;
use Yii;
use yii\base\Component;
use frontend\modules\jet\components\Data;
use frontend\modules\jet\components\Jetrepricing;
use frontend\modules\jet\components\Jetappdetails;

class Jetproductinfo extends component
{
	
	public static function createoption($product,$carray,$jetHelper,$fullfillmentnodeid,$merchant_id,$mappedAttr=[])
	{	
		/*
			Variables initialization area
		*/
		$newCustomPrice = $status_code = $updatePriceType = $queryObj = $error = $resultDes = '';	
		$updatePriceValue = $count = $product_qty = 0;
		$isParent = false;
		$setCustomPrice = $options = $responseOptions = [];		

		$setCustomPrice = Data::sqlRecords('SELECT `data`,`value` from `jet_config` where merchant_id="'.$merchant_id.'" AND data="set_price_amount"','one','select');
				
		if (is_array($setCustomPrice) && isset($setCustomPrice['value']))
		{
			$newCustomPrice=$setCustomPrice['value'];
		}
				
		if(trim($newCustomPrice)!="")
		{			
			$customPricearr = [];
			$customPricearr = explode('-',$newCustomPrice);
			$updatePriceType = $customPricearr[0];
			$updatePriceValue = $customPricearr[1];
			unset($customPricearr);
		}
			        
        $query ="SELECT `option_id`,`option_title`,`option_sku`,`jet_option_attributes`,`option_image`,`option_qty`,`option_weight`,COALESCE(`update_option_price`,`option_price`) as `option_price`,`option_unique_id`,`barcode_type`,`asin`,`option_mpn`,`jet_option_attributes`,`variant_option1`,`variant_option2`,`variant_option3`,`new_variant_option_1`,`new_variant_option_2`,`new_variant_option_3` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND product_id='".$product->id."'";
        $options = Data::sqlRecords($query,"all","select");
		if(is_numeric($product->qty) && $product->qty>0){
			$product_qty=$product->qty;
		}
        if(is_array($options) && count($options)>0)
        {
        	$eligibleVariants = [];
	        foreach($options as $val)
			{			    
			    $isMapped = $is_variation = false;				
				$SKU_Array = $unique = $Attribute_arr = $Attribute_array = $_uniquedata = $price = $node = $node1 = $inventory = $_uniquedata = [];
				$asin = $upc = $mpn = $type = $description = $parentmainImage = "";
				$option_weight = 0;

				$val=(object)$val;
				$sku=$val->option_sku;
				if($sku=="")
					continue;
				
				$upc = $val->option_unique_id;
				$asin = $val->asin;
				$option_weight =(float)$val->option_weight; 
				$mpn = $val->option_mpn;
				$brand=$product->vendor;
												
				$type=self::checkUpcType($upc);
				
				if($product->sku==$sku)
				{
					$isParent=true;
					$name=$product->title;
				}				
				else
				{
					$name=$product->title.'-'.$val->option_title;
				}
				$SKU_Array['product_title']=$name;
				$nodeid = (int)$product->fulfillment_node;
				if($product->jet_attributes)
				{
					$isMapped=true;
					$attribute=$product->jet_attributes;
					$attribute_opt=[];
					$attribute_opt=json_decode($val->jet_option_attributes,true);
					$Attribute_arr=json_decode($attribute,true);
				}
				else
				{
					$isMapped=false;
				}
				$SKU_Array['jet_browse_node_id']=$nodeid;
				if(isset($carray[$sku]['upc_var']) && $carray[$sku]['upc_var'])
				{
					$_uniquedata=array("type"=>$type,"value"=>$upc);
					$unique['standard_product_code']=$_uniquedata['value'];
					$unique['standard_product_code_type']=$_uniquedata['type'];
					$SKU_Array['standard_product_codes'][]=$unique;
				}
				if($asin!=null && isset($carray[$sku]['asin_var']) && $carray[$sku]['asin_var'])
				{
					$SKU_Array['ASIN']=$asin;
				}
				if($mpn!=null && isset($carray[$sku]['mpn_var']) && $carray[$sku]['mpn_var'])
				{
				    $SKU_Array['mfr_part_number']=$mpn;
				}
				$SKU_Array['manufacturer']=$brand;
				if(is_float($option_weight) && $option_weight>=0.01)
				{
					$SKU_Array['shipping_weight_pounds']=(float)$option_weight;
				}
				$SKU_Array['multipack_quantity']= 1;
				$SKU_Array['brand']=$brand;				
				$description=$product->description;
				//trim description string more than 2000
				if(strlen($description)>2000)
					$description=$jetHelper->trimString($description, 2000);
				$SKU_Array['product_description']=$description;
				//send images
				
				$kmain=0;
				$images=[];
				$images=explode(',',$product->image);

				if($product->image!="" && count($images)>0)
				{
					foreach($images as $key=>$value)
					{
						if(self::checkRemoteFile($value)==true)
						{
							$kmain=$key;
							$parentmainImage=$value;
							break;
						}
					}
				}
				if($product->sku==$sku)
	    		{
	    			$SKU_Array['main_image_url']=$parentmainImage;
	    			$SKU_Array['swatch_image_url']=$parentmainImage;
		    		if(count($images)>1)
		    		{
		    			$i=1;
		    			foreach($images as $key=>$value)
		    			{
		    				if($key==$kmain)
		    					continue;
		    				if($i>8)
		    					break;
		    				if($value!='' && self::checkRemoteFile($value)==true){
		    					
		    					$SKU_Array['alternate_images'][]= array(
		    							'image_slot_id'=>(int)$i,
		    							'image_url'=> $value
			    					);
		    					$i++;
		    				}
		    			}
		    		}
		    	}
	    		else
	    		{
	    			if($val->option_image!="" && self::checkRemoteFile($val->option_image)==true)
	    			{
	    				
	    				$SKU_Array['main_image_url']=$val->option_image;
	    				$SKU_Array['swatch_image_url']=$val->option_image;
	    			}else{
	    				$SKU_Array['main_image_url']=$parentmainImage;
	    				$SKU_Array['swatch_image_url']=$parentmainImage;
	    			}	    				
	    		}
	    		if($isMapped)
	    		{
	    			foreach ($Attribute_arr as $key=>$value)
					{
					    $attr_val="";
					    if(is_array($attribute_opt) && array_key_exists($value[0],$attribute_opt)){
					        $attr_val=$attribute_opt[$value[0]];
					    }else{
					        continue;
					    }
					    if(!isset($responseOptions['variation_refinements']) || !in_array($value[0],$responseOptions['variation_refinements']))
						{	
							$responseOptions['variation_refinements'][]=(int)$value[0];
						}
						if(count($value)==1)
						{
							$Attribute_array[] = array(
									'attribute_id'=>(int)$value[0],
									'attribute_value'=>$attr_val
		    				);
		    			}
		    			elseif(count($value)==2)
		    			{
							$Attribute_array[] = array(
								'attribute_id'=>(int)$value[0],
									'attribute_value'=>$attr_val,
									'attribute_value_unit'=>$value[1]
			    			);
			    		}
			    	}
	    		}
	    		else
	    		{
	    			foreach ($mappedAttr as $k=>$map_val)
					{
						if(!isset($responseOptions['variation_refinements']) || !in_array($k,$responseOptions['variation_refinements']))
						{	
							$responseOptions['variation_refinements'][]=(int)$k;
						}
						$Attribute_array[] = array(
							'attribute_id'=>(int)$k,
							'attribute_value'=>$map_val,
		    			);
					}
	    		}
				//file log for product option upload
				$path=\Yii::getAlias('@webroot').'/var/jet/product/upload/'.$merchant_id.'/variant/'.$product->sku.'<=>'.$sku;
				if(!file_exists($path)){
				    mkdir($path,0775, true);
				}
				if(!empty($SKU_Array))
				{
				    $filenameOrig = $responsedata = $status_code = "";
				    $response = [];

				    $status_code=false;
					if(count($Attribute_array)>0)
						$SKU_Array['attributes_node_specific'] = $Attribute_array; // add attributes details
				
					
					$filenameOrig=$path.'/Sku.log';
					$fileOrig=fopen($filenameOrig,'w');
					fwrite($fileOrig,"\n product sku: ".$sku."\n".json_encode($SKU_Array));
					
					$responsedata = $jetHelper->CPutRequest('/merchant-skus/'.rawurlencode($sku),json_encode($SKU_Array),$merchant_id,$status_code);
					fwrite($fileOrig,PHP_EOL." sku status code : ".$status_code.PHP_EOL."product sku response from jet : => ".$responsedata.PHP_EOL);
					fclose($fileOrig);
					
					$response=json_decode($responsedata,true);
					if(count($response)==0 && $status_code==202)
					{
						$price = [];
						$price['price']=(float)$val->option_price;
						if($price['price']==""){
							$price['price']=(float)$product->price;
						}
						
						// change new price
						if($updatePriceType && $updatePriceValue!=0)
						{
							$updatePrice = 0.00;
							$updatePrice = self::priceChange($price['price'],$updatePriceType,$updatePriceValue);
							if($updatePrice!=0)
								$price['price'] = (float)$updatePrice;
						}
						
						$node['fulfillment_node_id']=$fullfillmentnodeid;
						$node['fulfillment_node_price']=$price['price'];
						$price['fulfillment_nodes'][]=$node;
						
						$filenameOrig="";$fileOrig="";
						$filenameOrig=$path.'/Price.log';
						$fileOrig=fopen($filenameOrig,'w');
						fwrite($fileOrig,"\n".date('d-m-Y H:i:s')."\n".json_encode($price));

						$responsePrice = [];
						$responseData = $status_code = "";
						
						$responseData = $jetHelper->CPutRequest('/merchant-skus/'.rawurlencode($sku).'/price',json_encode($price),$merchant_id,$status_code);
						$responsePrice=json_decode($responseData,true);
						fwrite($fileOrig,"\n price status code : ".$status_code.PHP_EOL." price response from jet : => ".$responseData);						
						fclose($fileOrig);
						if(count($responsePrice)==0 && $status_code==202)
						{
							$qty="";
							$qty= $val->option_qty;
							if(trim($qty)=="")
							{
								$qty=trim($product_qty);
							}
							elseif(is_numeric($qty) && $qty<0)
							{
								$qty=0;
							}
							$inventory = $responseInventory = [];
							$node1['fulfillment_node_id']=$fullfillmentnodeid;
							$node1['quantity']=(int)$qty;
							$inventory['fulfillment_nodes'][]=$node1;
							
							$responseData = $filenameOrig = $fileOrig = "";
							$filenameOrig=$path.'/Inventory.log';
							$fileOrig=fopen($filenameOrig,'w');
							fwrite($fileOrig,"\n".date('d-m-Y H:i:s')."\n".json_encode($inventory));
							$status_code=true;
							$responseData = $jetHelper->CPutRequest('/merchant-skus/'.rawurlencode($sku).'/inventory',json_encode($inventory),$merchant_id,$status_code);
							$responseInventory=json_decode($responseData,true);
							fwrite($fileOrig,"\n inventory status code : ".$status_code.PHP_EOL." inventory response from jet : => ".$responseData);							
							fclose($fileOrig);
							if($status_code!=202)
							{
								$count++;
								$uploadInventoryError=($responseInventory||isset($responseInventory['errors']))?json_encode($responseInventory):"HTTP Response Code: ".$status_code;    
								$error.= "Error in inventory data for ".$sku.": ".$uploadInventoryError."\n";
								continue;
							}
						}
						else{
							$count++;
							$uploadPriceError=($responsePrice||isset($responsePrice['errors']))?json_encode($responsePrice):"HTTP Response Code: ".$status_code;    
							$error.= "Error in price data for ".$sku.": ".$uploadPriceError."\n";
							continue;
						}
					}
					else
					{
						$count++;
						$uploadSkuError=($response||isset($response['errors']))?json_encode($response):"HTTP Response Code: ".$status_code;
						$error.= "Error in sku data for ".$sku.": ".$uploadSkuError."\n";
						continue;
					}				
	    			// get sku on jet
					$responseData="";
					$responseSku = [];
					$status_code==true;
	    			$responseData = $jetHelper->CGetRequest('/merchant-skus/'.rawurlencode($sku),$merchant_id,$status_code);
	    			$responseSku=json_decode($responseData,true);
	    			if(count($responseSku)>0 && $status_code==200)
	    			{
	    				if($product->sku!=$sku)
	    					$responseOptions['children_skus'][]=$sku;
	    			}
				}
		    }
		    unset($options,$eligibleVariants,$unique,$Attribute_arr,$Attribute_array,$_uniquedata,$price,$node,$node1,$inventory);
		    if($error!="" && $count>0)
	    	{
	    		$responseOptions['errors']=$error;
	    	}
	    	$responseOptions['relationship']='Variation';
    		
		}else{
			$responseOptions['errors']="No Variant Options Available";
		}    
    	return $responseOptions;
    }

    public static function checkBeforeDataPrepare($product=[],$merchant_id="")
    {
        $carray = $Errors = [];
        $carray['success']=false;
        $carray['error']="";
        $cflag=0;
        if($product && trim($product->type))
        {
        	$image=trim($product->image);
            $countImage=0;
            $brand=trim($product->vendor);
            $nodeid = $product->fulfillment_node;
            $sku=$product->sku;
            $title=trim($product->title);
            $imageArr=[];
            $ImageFlag=false;
            $imageArr=explode(',',$image);
            //If all images all broken
            if($image!="" && count($imageArr)>0)
            {
                foreach ($imageArr as $value){
                    if(self::checkRemoteFile($value)==false)
                        $countImage++;
                }
                if(count($imageArr)==$countImage)
                    $ImageFlag=true;
            }
            if($sku=='')
            {
            	$Errors['sku_error']="Missing/Invalid Product Sku,";
                $Errors['sku']=$product->sku;
                $cflag++;
            }
            if(strlen($title)<5 || strlen($title)>500)
            {
            	$Errors['title_error']="Invalid Title (between 5 to 500 characters), ";
                $Errors['title']=$product->sku;
                $cflag++;
            }
        	if($brand=='')
        	{
            	$Errors['brand_error']="Missing brand,";
                $Errors['brand']=$product->sku;
                $cflag++;
            }
            if($nodeid==''){
            	$Errors['node_id_error']="Missing Jet Browse Node,";
                $Errors['node_id']=$product->sku;
                $cflag++;
            }
            if($image=='' || $ImageFlag){
            	$Errors['image_error']="Missing or Invalid Image,";
                $Errors['image']=$product->sku;
                $cflag++;
            }
            if(trim($product->type)=="simple")
            {
                // Variable initialization section
                $upc = $asin = $price = "";
                $upc_err = $mpn_err = $existUpc = $existAsin = $asinFlag = $existMpn = false; 
                $qty = 0;
                // end 
                $qty=trim($product->qty);
                $price=trim($product->price);
                $upc = trim($product->upc);
                $asin = trim($product->ASIN);
                $mpn = trim($product->mpn);
                if($upc=='' && $asin=='' && $mpn=='')
                {
                	$Errors['upc_error']="Missing Barcode or ASIN or MPN, ";
                    $Errors['upc']=$product->sku;
                    $cflag++;
                }
                if(($price<=0 || ($price && !is_numeric($price))) || trim($price)==""){
                	$Errors['price_error']="Invalid Price,";
                    $Errors['price']=$product->sku;
                    $cflag++;
                }               
                $type=self::checkUpcType($upc);
                if($type!="")
                    $existUpc=self::checkUpcSimple($upc,$product->id,$merchant_id);
                //check ASIN is unique
                $existAsin=self::checkAsinSimple($asin,$product->id,$merchant_id);
                $existMpn=self::checkMpnSimple($mpn,$product->id,$merchant_id);
                $isExistId=false;
                if($upc!='' && strlen($upc)>0 && $type!='' && !$existUpc){
                	$isExistId=true;
                	$carray['upc_simp']=true;
                }
                if($asin!="" && strlen($asin)==10 && ctype_alnum ($asin)  && !$existAsin)
                {
                	$isExistId=true;
                	$carray['asin_simp']=true;
                }
                if($mpn!="" && strlen($mpn)<=50 && !$existMpn)
                {
                	$isExistId=true;
                	$carray['mpn_simp']=true;
                }
                if(!$isExistId)
                {
                	$Errors['asin_error_info']="Invalid/Missing Barcode or ASIN or MPN, ";
                	$Errors['upc']=$product->sku;
                    $cflag++;
                }
            }
            elseif(trim($product->type)=="variants")
            {
                $par_price  = "";
                $c_par_price = false;
                $options = [];
                if($par_price<=0 || (trim($par_price) && !is_numeric($par_price)) || trim($par_price)==""){
                    $c_par_price=false;
                }else{
                    $c_par_price=true;
                }                
                $sqlNew = "SELECT `option_id`,`option_sku`,`option_qty`,COALESCE(`update_option_price`,`option_price`) as `option_price`,`option_unique_id`,`asin`,`option_mpn` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  product_id='".$product->id."'";
                $options = Data::sqlRecords($sqlNew,'all','select');
                
                if(is_array($options) && count($options)>0)
                {
                    foreach($options as $pro)
                    {
                        $upc = $asin = $nodeid = $opt_sku = $qty = "";
                        $price = 0.00;                        
                        $upc_err = $mpn_err = false;

                        $opt_sku=trim($pro['option_sku']);
                        $qty=trim($pro['option_qty']);
                        if($qty=="")
                        	$qty=0;
                        $price=trim($pro['option_price']);
                        $upc = trim($pro['option_unique_id']);
                        $asin = trim($pro['asin']);
                        $mpn = trim($pro['option_mpn']);
                        if($opt_sku==""){
                        	$Errors['sku_error_var'][]=$opt_sku;
                        	$Errors['sku']=$product->sku;
                        	$cflag++;
                        }
                        if($upc=='' && $asin=='' && $mpn==''){
                        	$Errors['upc_error_var'][]=$opt_sku;
                            $Errors['upc']=$product->sku;
                            $cflag++;
                        }
                        if(trim($price) && !is_numeric($price)){
                        	$Errors['price_error_var'][]=$opt_sku;
                            $Errors['price']=$product->sku;
                            $cflag++;
                        }
                        if((!$c_par_price && trim($price)=="") || (!$c_par_price && trim($price)<=0)){
                        	$Errors['price_error_var'][]=$opt_sku;
                            $Errors['price']=$product->sku;
                            $cflag++;
                        }
                        
                        //check upc type
                        $type="";
                        $existUpc = $existAsin = $asinFlag = $existMpn = false;
                        //check upc is unique
                        $type=self::checkUpcType($upc);
                        $productasparent=0;
                        if($product->sku==$pro['option_sku']){
                            $productasparent=1;
                        }
                        if($type!="")
                            $existUpc=self::checkUpcVariants($upc,$product->id,$pro['option_id'],$productasparent,$merchant_id);
                        //check ASIN is unique
                        $existAsin=self::checkAsinVariants($asin,$product->id,$pro['option_id'],$productasparent,$merchant_id);
                        $existMpn=self::checkMpnVariants($mpn,$product->id,$pro['option_id'],$productasparent,$merchant_id);
                        $isExistId = $attrNotMapped = false;
                        if($upc!='' && strlen($upc)>0 && $type!='' && !$existUpc){
                        	$isExistId=true;
        					$carray[$opt_sku]['upc_var']=true;
		                }
		                if($asin!="" && strlen($asin)==10 && ctype_alnum ($asin)  && !$existAsin)
		                {
		                	$isExistId=true;
		                	$carray[$opt_sku]['asin_var']=true;
		                }
		                if($mpn!="" && strlen($mpn)<=50 && !$existMpn)
		                {
		                	$isExistId=true;
		                	$carray[$opt_sku]['mpn_var']=true;
		                }
		                if(!$isExistId)
		                {
		                	$Errors['asin_error_info_var'][]=$opt_sku;
		                	$Errors['upc']=$product->sku;
		                    $cflag++;
		                }

                        $attr_ids = $jet_mapped = "";
                        $attr_ids_arr = $jet_mapped_arr = [];
                        $attr_ids=$product->attr_ids;
                        $jet_mapped=$product->jet_attributes;
                        if($attr_ids !=""){
                            $attr_ids_arr=json_decode($attr_ids,true);
                        }
                        if($jet_mapped !=""){
                            $jet_mapped_arr=json_decode($jet_mapped,true);
                        }
                        $acflag=0;
                        
                        if(is_array($attr_ids_arr) && count($attr_ids_arr)>0)
                        {
                            if(is_array($jet_mapped_arr) && count($jet_mapped_arr)>0)
                            {
                                foreach($attr_ids_arr as $k_a=>$v_a)
                                {
                                    if(array_key_exists(trim($v_a),$jet_mapped_arr) && $jet_mapped_arr[$v_a]!=""){
                                        $acflag++;
                                    }
                                }
                                if($acflag==0){                                	
                                	$attrNotMapped=true;                                    
                                }
                            }else{                            	
                                $attrNotMapped=true;
                            }
                            if($attrNotMapped)
                            {
                            	$resAttr = AttributeMap::getMappedOptionValues($pro['option_id'], $attr_ids, $product->product_type, $merchant_id);
                            	if(is_array($resAttr) && count($resAttr)>0)
                            	{
                            		$carray['attribute_mapped'] = $resAttr;
                            	}
                            	else
                            	{
                            		$Errors['attribute_mapping_error'][]=$opt_sku;
                                	$Errors['attribute_mapping']=$product->sku;
                                	$cflag++;
                            	}
                            }
                        }    
                    }
                }                 
            }
        }
        if($cflag==0){
            $carray['success']=true;
        }
        $carray['error']=$Errors;
        return $carray;
    }
    public static function getEligibleVariants($product="",$merchant_id="",$options)
    {
        $eligibleVariants=array();
        if(is_array($options) && count($options)>0)
        {
            $i=0;
            foreach($options as $val)
            {
                $attribute = $option_id = "";
                $option_id=trim($val['option_id']);
                $attribute=trim($val['jet_option_attributes']);
                if($i==0){
                    $eligibleVariants[$option_id]=$attribute;
                }else{
                    if(!in_array($attribute,$eligibleVariants)){
                        $eligibleVariants[$option_id]=$attribute;
                    }
                }
                $i++;
            }
        }
        return $eligibleVariants;
    }
    public static function checkproductnoattr($product,$merchant_id)
    {
        $carray=array();
        $carray['success']=false;
        $carray['error']="";
        $Errors=array();
        $cflag=0;
        
        if($product)
        {
            $upc="";
            $asin="";
            $price="";
            $qty="";
            $nodeid="";
            $upc_err=false;
            $mpn_err=false;
            $brand="";
            $sku="";
            $qty=trim($product->qty);
            $price=trim($product->price);
            $upc = trim($product->upc);
            $asin = trim($product->ASIN);
            $mpn = trim($product->mpn);
            $brand=trim($product->vendor);
            $nodeid = $product->fulfillment_node;
            $sku=$product->sku;
            $countImage=0;
            $image=trim($product->image);
            $imageArr=array();
            $ImageFlag=false;
            $imageArr=explode(',',$image);
            //If all images all broken
            if($image!="" && count($imageArr)>0){
                foreach ($imageArr as $value){
                    if(self::checkRemoteFile($value)==false)
                        $countImage++;
                }
                if(count($imageArr)==$countImage)
                    $ImageFlag=true;
            }
       		if($sku==''){
       			$Errors['sku_error']="Missing Product Sku,";
       			$Errors['sku']=$product->sku;
       			$cflag++;
       		}
       		if($upc=='' && $asin=='' && $mpn==''){
       			$Errors['upc_error']="Missing Barcode or ASIN or MPN,";
       			$Errors['upc']=$product->sku;
       			$cflag++;
       		}
       		if($brand==''){
       			$Errors['brand_error']="Missing brand,";
       			$Errors['brand']=$product->sku;
       			$cflag++;
       		}
       		if($nodeid==''){
       			$Errors['node_id_error']="Missing Jet Browse Node,";
       			$Errors['node_id']=$product->sku;
       			$cflag++;
       		}
       		if($image=='' || $ImageFlag){
       			$Errors['image_error']="Missing or Invalid Image,";
       			$Errors['image']=$product->sku;
       			$cflag++;
       		}
       		if(($price<=0 || ($price && !is_numeric($price))) || trim($price)==""){
       			$Errors['price_error']="Invalid Price,";
       			$Errors['price']=$product->sku;
       			$cflag++;
       		}
       		/*if(($qty && !is_numeric($qty))||trim($qty)==""||($qty<=0 && is_numeric($qty))){
       			$Errors['qty_error']="Invalid Qauntity,";
       			$Errors['qty']=$product->sku;
       			$cflag++;
       		} */ 	
            //check upc type
            $type="";
            $existUpc=false;
            $existAsin=false;
            $upcFlag=false;
            $asinFlag=false;
            $existMpn=false;
            //check upc is unique
            $type=self::checkUpcType($upc);
            if($type!="")
                $existUpc=self::checkUpcVariantSimple($upc,$product->id,$sku,$merchant_id);
            //check ASIN is unique
            $existAsin=self::checkAsinVariantSimple($asin,$product->id,$sku,$merchant_id);
            $existMpn=self::checkMpnVariantSimple($mpn,$product->id,$sku,$merchant_id);
          	if($upc!='' && strlen($upc)>0 && $type!='' && !$existUpc){
            	$carray['upc_simp']=true;
            }
            elseif($asin!="" && strlen($asin)==10 && ctype_alnum ($asin)  && !$existAsin)
            {
            	$carray['asin_simp']=true;
            }
            elseif($mpn!="" && strlen($mpn)<=50 && !$existMpn)
            {
            	$carray['mpn_simp']=true;
            }
            else{
            	$Errors['upc']=$product->sku;
            	$Errors['asin_error_info']="Invalid/Missing Barcode or ASIN or MPN, ";
                $cflag++;
            }
        }  
        if($cflag==0){
            $carray['success']=true;
        }       
        $carray['error']=$Errors;
        return $carray;
    }

	public static function checkCategoryAttributeNotExists($category_id="",$jetHelper=[],$merchant_id="")
	{
	    $response="";
	    $response = $jetHelper->CGetRequest('/taxonomy/nodes/'.$category_id.'/attributes');
	    $attributes=[];
	    $attributes=json_decode($response,true);
	    if($attributes && count($attributes)>0 && isset($attributes['attributes'])){
	        return false;
	    }
	    return true;
	}
    public static function getLeafCategoryId($jetBrowsenode="",$categoryModel="")
    {
    	$result="";
    	$result=$categoryModel->find()->where(['category_id'=>$jetBrowsenode])->one();
    	if($result)
    	{
    		if($result->level==1)
    		{
    			$resultLeafId="";
    			$resultLeafId=$categoryModel->find()->where(['parent_id'=>$jetBrowsenode,'level'=>2])->one();
    			if($resultLeafId)
    				return $resultLeafId->category_id;
    			
    		}elseif($result->level==0)
    		{
    			$resultRLeafId="";
    			$resultRLeafId=$categoryModel->find()->where(['root_id'=>$jetBrowsenode,'level'=>2])->one();
    			if($resultRLeafId)
    				return $resultRLeafId->category_id;    			

    		}
    	}
    	return false;
    }
    public static function checkRemoteFile($url)
    {
    	$hostname = Yii::getAlias('@hostname');
		if(strpos($url,'upload/images')!== false) 
		{
			$url=$hostname.Yii::$app->getUrlManager()->getBaseUrl().'/'.$url;
		}
    	stream_context_set_default([
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
    
        public static function productUpdateData($result,$data,$jetHelper,$fullfillmentnodeid,$merchant_id,$handle,$customPrice="",$newCustomPrice="", $onWalmart=false,$onNewEgg=false,$import_status=false)
    {    	
    	$variants_ids = $archiveSkus = $updateProduct = $skuChangeData = [];
    	$product_type = $data['product_type'];
    	$message="Hello";
    	fwrite($handle, 'Product-Id: '.$data['id'].PHP_EOL.PHP_EOL);
    	if($data['product_type']=="" || ($import_status=="published" && is_null($data['published_at'])))
	    {
	    	//delete product
	    	$message= "empty product-type".PHP_EOL;
	    	fwrite($handle, $message);
	    	self::deleteVariant($data['id'],$result,$jetHelper,$merchant_id,true,$onWalmart,$onNewEgg);
	    	//save product info in product_import_error table
	    	$type="";
	    	if($data['product_type']=="")
	    		$type="product_type";
	    	else
	    		$type="hidden_product";
	    	self::insertImportErrorProduct($data['id'],$data['title'],$type,$merchant_id);
	    	return $message;
	    }
    	$updatePriceType="";
    	$updatePriceValue=0;
    	$product_id=$data['id'];
    	$variants=$data['variants'];
    	$images=$data['images'];
    	$imagArr = $OldImages = [];
    	$imageChange = false;
    	$flagChange=false;
    	$OldImages=explode(',',$result['image']);
    	$product_images="";
    	$old_description = stripslashes(strip_tags($result['description']));
    	$new_description = preg_replace("/<script.*?\/script>/", "", $data['body_html'])? : $data['body_html'];
    	
    	$old_title= stripslashes(strip_tags($result['title']));
    	$new_title= strip_tags($data['title']);
    	if(is_array($images))
    	{
	    	foreach ($images as $valImg)
	    	{
	    		if(!in_array($valImg['src'],$OldImages)){
	    			$imageChange=true;
	    		}
	    		$imagArr[]=$valImg['src'];
	    	}
	    	$product_images=implode(',',$imagArr);
    	}
    	unset($OldImages);
    	$changeParentCat=false;
    	$productTypeChange=false;
    	$isUpdateTitle=false;
    	$isUpdateDescription=false;
    	//check title and description updated or not
    	if($old_title!=$new_title)
    	{
    		$isUpdateTitle=true;
    	}
    	if($old_description!=$new_description)
    	{
    		$isUpdateDescription=true;
    	}
    	//if product-type is changed and need to change for walmart and newegg
    	if($result['product_type']!=$product_type)
    	{ 
    		$message= "change product-type".PHP_EOL;
    		fwrite($handle, $message);
    		$productTypeChange=true;
			//$updateProduct['product_type']=$product_type;
			$modelmap = Data::sqlRecords('SELECT category_id from `jet_category_map` where merchant_id="'.$merchant_id.'" and product_type="'.addslashes($product_type).'" LIMIT 0,1','one','select');
			if(isset($modelmap['category_id']))
			{
				if($modelmap['category_id']!=$result['fulfillment_node'])
				{
					$message="change product category".PHP_EOL;
					fwrite($handle, $message);
					//$updateProduct['fulfillment_node']=$modelmap['category_id'];
					$changeParentCat=true;
				}
			}
			else
			{
				//insert new product-type
				$message= "add new product-type".PHP_EOL;
				fwrite($handle, $message);
				Data::sqlRecords('INSERT INTO `jet_category_map`(`merchant_id`,`product_type`)VALUES("'.$merchant_id.'","'.addslashes($product_type).'")');
			}
		}
		$attr_ids="";
    	if(isset($data['options']) && count($data['options'])>0)
    	{
    		$options=$data['options'];
			$attrId=array();
			$attrValue=array();
			$attFlag=false;
			$attrValue=json_decode($result['attr_ids'],true);
			if(is_array($attrValue) && count($attrValue)>0){
				foreach($options as $key=>$val)
				{
					if(!in_array($val['name'], $attrValue))
					{
						$attFlag=true;
						break;
					}
				}
			}
			if($attFlag)
			{
				//send request to archive all sku(s) on jet/walmart
				//self::sendProductRequestToMarketplace($product_id,$result,"delete",false,$onWalmart,$onNewEgg,$merchant_id);
				if($jetHelper)
					self::archiveAllVariants($product_id,$jetHelper,$merchant_id);
				foreach($options as $key=>$val)
				{
					if($val['name']!="Title")
					{
						$attrId[$val['id']]=$val['name'];
					}
				}
				if(is_array($attrId) && count($attrId)>0){
					$attr_ids=json_encode($attrId);
				}
				$message= "wrong variant attr".PHP_EOL;
				fwrite($handle, $message);
				/*$message.=self::addNewVariants($data,$product_id,$jetHelper,$merchant_id,$fullfillmentnodeid,$onWalmart,$onNewEgg);
				return $message;*/
			}
    	}
    	$variantCount=0;
    	$variantIds=[];
    	$skus=[];
    	$deleteflag=false;
    	$deleteProductVariants=[];
    	foreach ($variants as $key => $value) 
    	{
    		//check product variants
    		$message = "option-id: ".$value['id']."---- Option-sku: ".$value['sku'].PHP_EOL;
	    	fwrite($handle, $message);
    		$query="SELECT option_id,option_title,option_sku,jet_option_attributes,option_image,option_qty,option_weight,option_price,option_unique_id,variant_option1,variant_option2,variant_option3 FROM `jet_product_variants` WHERE option_id='".$value['id']."' LIMIT 0,1";
    		$vresult=Data::sqlRecords($query,"one","select");
    		$isVarExist=false;
    		$skuChange=false;
    		$option_image_id=isset($value['image_id'])?$value['image_id']:'';
			$option_variant1=isset($value['option1'])?$value['option1']:'';
			$option_variant2=isset($value['option2'])?$value['option2']:'';
			$option_variant3=isset($value['option3'])?$value['option3']:'';
			$option_title=isset($value['title'])?$value['title']:'';
			$weight=(float)Jetappdetails::convertWeight($value['weight'],$value['weight_unit']);
			$weight=round($weight,2);
    		if(isset($vresult['option_id']))
    		{
    			$isVarExist = true;
    		}
    		if(!self::validateSku($value['sku'],$value['id'],$merchant_id) || in_array($value['sku'], $skus))
    		{
    			$message= "duplicate product sku".PHP_EOL;
	    		fwrite($handle, $message);
    			continue;
    		}
    		if($value['sku'] == "" && (($isVarExist && $vresult['option_sku']) || $result['sku']))
	    	{
	    		$message= "blank product sku".PHP_EOL;
	    		fwrite($handle, $message);
	    		if($isVarExist)
	    		{
	    			if($result['variant_id']==$vresult['option_id'])
					{
						//delete whole product
						$message= "delete main variant product sku".PHP_EOL;
						fwrite($handle, $message);
						self::deleteVariant($product_id,$result,$jetHelper,$merchant_id,true,$onWalmart,$onNewEgg);
					}
					//delete only variant option
					//$deleteProductVariants[]=$vresult['option_id'];
	    		}
	    		else
	    		{
	    			$message= "delete simple product sku".PHP_EOL;
	    			fwrite($handle, $message);
	    			self::deleteVariant($product_id,$result,$jetHelper,$merchant_id,true,$onWalmart,$onNewEgg);
	    		}
	    		//entry product_import_error
	    		self::insertImportErrorProduct($data['id'],$data['title'],'sku',$merchant_id);
	    		continue;
	    	}
	    	if(($isVarExist && $value['sku']!=$vresult['option_sku']) || $value['sku']!=$result['sku'])
	    	{
	    		if(($isVarExist && $value['id']==$vresult['option_id']) || $value['id']==$result['variant_id']){
	    			$message= "change product sku".PHP_EOL;
		    		fwrite($handle, $message);
		    		if($isVarExist)
						$sku=$vresult['option_sku'];
		    		else
		    			$sku=$result['sku'];
		    		$updateProduct[$value['id']]['sku']=$value['sku'];
		    		$archiveSkus[]=$sku;
	    		}
	    		/*else
	    		{
	    			//delete old variant/simple product option and add new option
	    			$message= "delete simple/variant product and add new".PHP_EOL;
	    			fwrite($handle, $message);
	    			$message = self::addNewVariants($data,$result,$product_id,$jetHelper,$merchant_id,$fullfillmentnodeid,$onWalmart,$onNewEgg);
	    			continue;
	    		}*/
	    		//send data to retire on walmart
	    		//continue;
	    	}
	    	$updateProduct[$value['id']]['orig_sku'] = $result['sku'];
	    	if($productTypeChange){
	    		$updateProduct[$value['id']]['product_type']=$product_type;
	    	}
	    	if($attr_ids){
	    		$updateProduct[$value['id']]['attr_ids']=$attr_ids;
	    	}
	    	if($isUpdateTitle){
	    		$message= "change product title".PHP_EOL;
	    		fwrite($handle, $message);
	    		$updateProduct[$value['id']]['title']=$new_title;
	    	}
    		if($isUpdateDescription){
    			$message= "change product description".PHP_EOL;
	    		fwrite($handle, $message);
    			$updateProduct[$value['id']]['description']=$new_description;
    		}
	    	if($changeParentCat)
	    	{
	    		$updateProduct[$value['id']]['category'] = $modelmap['category_id'];
	    	}
    		//if(!$configBarcode)
    		//{
    			$barcodeValid = self::validateBarcode($value['barcode']);
	    		if($barcodeValid && (($isVarExist && $value['barcode']!=$vresult['option_unique_id']) || $value['barcode']!=$result['upc']))
				{
					$message= "update product barcode \n";
					fwrite($handle, $message);
					$updateProduct[$value['id']]['barcode'] = $value['barcode'];
				}
    		//}
			$imageFlag=false;
			$option_image_url="";
			if(is_array($images) && count($images)>0 && $option_image_id)
			{
    			foreach ($images as $value2)
    			{
    				if($value2['id'] == $option_image_id){
    					$option_image_url=$value2['src'];
    					break;
    				}
    			}
			}
			if($imageChange || ($isVarExist && $option_image_url))
			{
				$message= "update product image".PHP_EOL;
				fwrite($handle, $message);
		    	$updateProduct[$value['id']]['main_image']=$option_image_url;
				$updateProduct[$value['id']]['product_image']=$product_images;
				/*if(is_array($imagArr) && count($imagArr)>1)
	    		{
	    			$updateProduct[$value['id']]['alternate_images']=$imagArr;
	    		}*/
			}
			//update qty
			if(($isVarExist && $value['inventory_quantity']!=$vresult['option_qty']) || $value['inventory_quantity']!=$result['qty'])
			{
				$message= "update product inventory old qty:".$result['qty']."/".$vresult['option_qty']."--New qty:".$value['inventory_quantity'].PHP_EOL;
				fwrite($handle, $message);
				$updateProduct[$value['id']]['qty'] = $value['inventory_quantity'];
				if($value['inventory_quantity']<0)
					$option_qty = 0;
				else
					$option_qty=$value['inventory_quantity'];
				if($jetHelper)
					$message.=self::updateQtyOnJet($value['sku'],$option_qty,$jetHelper,$fullfillmentnodeid,$merchant_id);
				/*if($onWalmart)
					self::sentCurlRequest(['qty'=>$option_qty,'sku'=>$value['sku'],'type'=>'inventory','merchant_id'=>$merchant_id]); */
			}
			if($data['vendor']!=$result['vendor'])
			{
				$message= "update vendor".PHP_EOL;
				fwrite($handle, $message);
				$updateProduct[$value['id']]['vendor']=$data['vendor'];
			}
			//send updated price on walmart
			$isPriceChange=false;
			if(($isVarExist && $value['price']!=$vresult['option_price']) || $value['price']!=$result['price'])
			{
				$message= "update price".PHP_EOL;
				fwrite($handle, $message);
				$isPriceChange=true;
				$updateProduct[$value['id']]['price']=(float)$value['price'];
				/*self::sentCurlRequest(['price'=>$value['price'],'sku'=>$value['sku'],'type'=>'price','merchant_id'=>$merchant_id]); */
			}
			//send updated price on jet
			if(!$customPrice && $isPriceChange && $jetHelper)
			{
				// change new price
				$option_price_new=$value['price'];
				/*if($updatePriceType && $updatePriceValue!=0)
				{
					$updatePrice=0;
					$updatePrice=self::priceChange($option_price_new,$updatePriceType,$updatePriceValue);
					if($updatePrice!=0)
						$option_price_new = $updatePrice;
				}*/
				if($result['type']=="simple")
					$option_price_new = self::getPriceToBeUpdatedOnJet($product_id, $merchant_id, $value['price'],$newCustomPrice, $result['type']);
				else
					$option_price_new = self::getPriceToBeUpdatedOnJet($value['id'], $merchant_id, $value['price'],$newCustomPrice, $result['type']);
				$message= "base price: ".$value['price'].", new price: ".$option_price_new.PHP_EOL;
				fwrite($handle, $message);
				$message.=self::updatePriceOnJet($value['sku'],(float)$option_price_new,$jetHelper,$fullfillmentnodeid,$merchant_id);
			}
			if(($isVarExist && $weight!=$vresult['option_weight']) || ($weight!=$result['weight']) )
			{
				$message= "update weight old:".$result['weight']."/".$vresult['option_weight']."--New weight:".$weight.PHP_EOL;
				fwrite($handle, $message);
				$updateProduct[$value['id']]['weight']=$weight;
			}
			$updateProduct[$value['id']]['variant_as_parent']=0;
			if($isVarExist)
			{
				if($option_title && $vresult['option_title']!=$option_title)
				{
					$message= "update option_title".PHP_EOL;
					fwrite($handle, $message);
					$updateProduct[$value['id']]['option_title']=$option_title;
				}
				if($option_variant1!="" && $vresult['variant_option1']!=$option_variant1)
				{ 
					$message= "update option-variant1".PHP_EOL;
					fwrite($handle, $message);
					$updateProduct[$value['id']]['variant_option1']=$option_variant1;
				}
				if($option_variant2!="" && $vresult['variant_option2']!=$option_variant2)
				{   
					$message= "update option-variant2".PHP_EOL;
					fwrite($handle, $message);
					$updateProduct[$value['id']]['variant_option2']=$option_variant2;
				}
				if($option_variant3!="" && $vresult->variant_option3!=$option_variant3)
				{ 
					$message= "update option-variant3".PHP_EOL;
					fwrite($handle, $message);
					$updateProduct[$value['id']]['variant_option3']=$option_variant3;
				}
				$message= "".PHP_EOL;
				fwrite($handle, $message);
				//send sku data on jet.com
    			if($result['variant_id']==$vresult['option_id'])
    			{
					$updateProduct[$value['id']]['variant_as_parent']=1;
    			}
			}	
	    	$variantCount++;
	    	$variantIds[]=$value['id'];
	    	$skus[]=$value['sku'];

    	}
    	//delete product variants if product change to simple product
    	$isSimple=false;
		if($variantCount==1 && $result['type']=="variants")
    	{
    		$isSimple=true;
    		$message= "change variant to simple product".PHP_EOL;
    		fwrite($handle, $message);
    		if(in_array($result['variant_id'], $variantIds))
    		{
    			$message= "if parent variant not deleted then delete only variants".PHP_EOL;
    			fwrite($handle, $message);
    			self::deleteVariant($product_id,$result,$jetHelper,$merchant_id,false,$onWalmart,$onNewEgg);
    		}
    		else
    		{
    			$message= "delete whole product if parent variant deleted and addnew product".PHP_EOL;
    			fwrite($handle, $message);
    			$message = self::addNewVariants($data,$result,$product_id,$jetHelper,$merchant_id,$fullfillmentnodeid,$onWalmart,$onNewEgg);
    			return $message;
    		}
    	}
    	elseif($variantCount>1)
    	{
    		$addVariantFlag=false;
    		if($result['type']=="simple")
    		{
    			$addVariantFlag=true;
    			$message= "change simple to variants product".PHP_EOL;
    			fwrite($handle, $message);
    			self::addVariant($product_id,$variantIds,$data,$images,$merchant_id,true,$onWalmart,$onNewEgg);
    		}
    		if(!$addVariantFlag)
    		{
    			$query="SELECT option_id FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  product_id='".$product_id."'";
    			$proVarAll=Data::sqlRecords($query,"all","select");
    			$availableIds=[];
    			$resultArray=[];
    			$availble_variants=[];
    			if($variantCount<count($proVarAll) || $variantCount>count($proVarAll))
	    		{
	    			foreach ($proVarAll as $v) 
	    			{
	    				$availble_variants[]=(int)$v['option_id'];
	    			}
	    			if($variantCount>count($availble_variants))
    				{
    					$message= "add variant  product".PHP_EOL;
    					fwrite($handle, $message);
    					$resultArray=array_diff($variantIds, $availble_variants);
    					if(is_array($resultArray) && count($resultArray)>0)
    					{
    						self::addVariant($product_id,$resultArray,$data,$images,$merchant_id,false,$onWalmart,$onNewEgg);
    					}
    				}
    				elseif($variantCount<count($availble_variants))
    				{
    					$message= "delete extra variant  product".PHP_EOL;
    					fwrite($handle, $message);
    					$resultArray=array_diff($availble_variants, $variantIds);
    					if(is_array($resultArray) && count($resultArray)>0)
    					{
    						//self::sendProductRequestToMarketplace($product_id,$result,"deleteById",$resultArray,$onWalmart,$onNewEgg,$merchant_id);
    						Data::sqlRecords("DELETE FROM `jet_product_variants` WHERE option_id IN('".implode(',', $resultArray)."')");
    					}
    				}
    			}
    		}    		
    	}
    	//update product data
    	if(is_array($updateProduct) && count($updateProduct)>0)
    	{
    		//self::sendProductUpdateRequestToMarketplace($product_id,$result,$updateProduct,$onWalmart,$onNewEgg,$merchant_id);
    		foreach ($updateProduct as $key => $pro_val) 
			{
	    		$updateResponseData=self::prepareUpdateData($pro_val);
				if(isset($updateResponseData['parent_query']))
				{
					$query="UPDATE `jet_product` SET ".$updateResponseData['parent_query']." WHERE variant_id='".$key."'";
					$message=PHP_EOL."Main product update query:".PHP_EOL.$query.PHP_EOL;
					fwrite($handle, $message);
					Data::sqlRecords($query);
				}
				if(isset($updateResponseData['child_query']))
				{
					$query="UPDATE `jet_product_variants` SET ".$updateResponseData['child_query']." WHERE option_id='".$key."'";
					$message=PHP_EOL."child product update query:".PHP_EOL.$query.PHP_EOL;
					fwrite($handle, $message);
					Data::sqlRecords($query);
				}
			}
    	}
    	return $message;
    }
    public static function prepareUpdateData($pro_val=[])
    {
		$queryPro="";
		$queryProVar="";
		if(isset($pro_val['sku']))
		{
			$queryPro.='sku="'.addslashes($pro_val['sku']).'",';
			$queryProVar.='option_sku="'.addslashes($pro_val['sku']).'",';
		}
		if(isset($pro_val['product_type'])){
			$queryPro.='product_type="'.addslashes($pro_val['product_type']).'",';
		}
		if(isset($pro_val['title'])){
			$queryPro.='title="'.addslashes($pro_val['title']).'",';
		}
		if(isset($pro_val['description']))
		{
			$queryPro.='description="'.addslashes($pro_val['description']).'",';
		}
		if(isset($pro_val['vendor'])){
			$queryPro.='vendor="'.addslashes($pro_val['vendor']).'",';
		}
		if(isset($pro_val['attr_ids'])){
			$queryPro.='attr_ids="'.addslashes($pro_val['attr_ids']).'",';
		}
		if(isset($pro_val['barcode']))
		{
			$queryPro.='upc="'.$pro_val['barcode'].'",';
			$queryProVar.='option_unique_id="'.$pro_val['barcode'].'",';
		}
		if(isset($pro_val['main_image'])){
			$queryPro.='image="'.addslashes($pro_val['product_image']).'",';
			$queryProVar.='option_image="'.addslashes($pro_val['main_image']).'",';
		}
		if(isset($pro_val['qty'])){
			$queryPro.='qty="'.(int)$pro_val['qty'].'",';
			$queryProVar.='option_qty="'.(int)$pro_val['qty'].'",';	
		}
		if(isset($pro_val['price'])){
			$queryPro.='price="'.(float)$pro_val['price'].'",';
			$queryProVar.='option_price="'.(float)$pro_val['price'].'",';	
		}
		if(isset($pro_val['weight'])){
			$queryPro.='weight="'.(float)$pro_val['weight'].'",';
			$queryProVar.='option_weight="'.(float)$pro_val['weight'].'",';	
		}
		if(isset($pro_val['option_title']))
		{
			$queryProVar.='option_title="'.addslashes($pro_val['option_title']).'",';	
		}
		if(isset($pro_val['variant_option1'])){
			$queryProVar.='variant_option1="'.addslashes($pro_val['variant_option1']).'",';	
		}
		if(isset($pro_val['variant_option2'])){
			$queryProVar.='variant_option2="'.addslashes($pro_val['variant_option2']).'",';	
		}
		if(isset($pro_val['variant_option3'])){
			$queryProVar.='variant_option3="'.addslashes($pro_val['variant_option3']).'",';	
		}
		$response=[];
		$queryPro=rtrim($queryPro,',');
		$queryProVar=rtrim($queryProVar,',');
		if($queryPro)
			$response['parent_query']=$queryPro;
		if($queryProVar)
			$response['child_query']=$queryProVar;	
		return $response;
    }
    public static function insertImportErrorProduct($id,$title,$type,$merchant_id)
    {
    	$checkExistProduct=Data::sqlRecords("SELECT `id` FROM `product_import_error` WHERE merchant_id='".$merchant_id."' AND  id='".$id."' LIMIT 0,1","one","select");
		if(!$checkExistProduct)
		{
			$query="INSERT INTO `product_import_error`(`id`, `merchant_id`, `missing_value`, `title`) VALUES ('".$id."','".$merchant_id."','".$type."','".addslashes($title)."')";
			Data::sqlRecords($query);
		}
    }

    public static function updatePriceOnJet($sku,$price,$jetHelper,$fullfillmentnodeid,$merchant_id,&$batchResponse=false)
    {
    	try
    	{
    		$response  = [];
    		$message="";
    		$status=true;
    		$message.= "\n<!-----------------updatePriceOnJet function Start--------------------->\n";
	    	if(self::checkSkuOnJet($sku,$jetHelper,$merchant_id)==false){
	    		return "Product not available on jet";
	    	}
	    	$price = Jetrepricing::getRepricedPrice($sku, $price, $merchant_id);
	    	$priceArray=$priceinfo=array();
	    	$priceArray['price']=(float)$price;
	    	$priceinfo['fulfillment_node_id']=$fullfillmentnodeid;
	    	$priceinfo['fulfillment_node_price']=(float)$price;
	    	$priceArray['fulfillment_nodes'][]=$priceinfo;
	    	$responsePrice="";
	    	$responsePrice = $jetHelper->CPutRequest('/merchant-skus/'.rawurlencode($sku).'/price',json_encode($priceArray),$merchant_id,$status);
	    	$responsePrice=json_decode($responsePrice,true);
	    	if($status==202)
	    		$batchResponse=$status;
	    	if(isset($responsePrice['errors']) || $status!=202)
	    	{
	    		$message.= "\nPirce not updated on jet. Error:".json_encode($responsePrice['errors'])."\n";
	    		//return $sku."=>".$responsePrice['errors'];
	    	}
	    	$message.= "\n<!------------------updatePriceOnJet function End---------------------->\n ";
	    	$response['status_code'] = $status;
	    	$response['message'] = $message;
	    	return json_encode($response);
    	}
    	catch(Exception $e)
    	{	
    		return $message.= "\n--price exception error--".$e->getMessage();

    	}
    	
    }
    public static function updateQtyOnJet($sku,$qty,$jetHelper,$fullfillmentnodeid,$merchant_id,&$batchResponse=false)
    {
    	$response1 = [];
    	$message="";
    	$message.= "<!----------------------updateQtyOnJet function Start----------------------->".PHP_EOL;
    	$jetResponse=self::checkSkuOnJet($sku,$jetHelper,$merchant_id,true);
    	$status=true;
    	if($jetResponse==false){
    		return "Product not available on jet";
    	}
    	if((isset($jetResponse['is_archived']) && $jetResponse['is_archived']==true) || (isset($jetResponse['status']) && $jetResponse['status']=='Archived'))
    	{
    		return "already product archived no need to update inventory";
    	}
    	$configSetting = self::getConfigSettings($merchant_id);
    	if(isset($configSetting['inventory']) && $configSetting['inventory'])
    	{
    		$message.= "enable threshold inventory value: ".$configSetting['inventory'].PHP_EOL; 
    		if($qty<=$configSetting['inventory'])
    			$qty=0;
    	}
	    $inv=$inventory=[];
	    $inv['fulfillment_node_id']=$fullfillmentnodeid;
	    $inv['quantity']=(int)$qty;
	    $inventory['fulfillment_nodes'][]=$inv;
	    $responseInventory="";
	    $response = $jetHelper->CPutRequest('/merchant-skus/'.rawurlencode($sku).'/inventory',json_encode($inventory),$merchant_id,$status);
	    $responseInventory = json_decode($response,true);
	    //if($status==202)
	    	$batchResponse=$status;

	    if(isset($responseInventory['errors']) || $status!=202)
	    {
	    	$message.=  "\n Inventory not updated on jet. Error". json_encode($responseInventory['errors'])."\n";
	    	//return $sku."=>".$responseInventory['errors'];
	    }
	    $message.= "product inventory sucessfully send on jet".PHP_EOL;
	    $message.= "<!----------------------updateQtyOnJet function End--------------------->\n";
	    $response1['status_code'] = $status;
    	$response1['message'] = $message;
    	return json_encode($response1);
	    // return $message;
    }
    public static function archiveProductOnJet($skus,$jetHelper,$merchant_id,$fullfillmentnodeid="")
    {
    	$message="";
    	$message.= "\n<!---------------archiveProductOnJet function Start---------------->\n";
    	$successArchive=[];
    	$status=true;
    	if(is_array($skus) && count($skus)>0)
    	{
    		foreach ($skus as $sku)
    		{
    			$newResponse="";
    			$newResponsearr=array();
    			$newResponse=$jetHelper->CPutRequest('/merchant-skus/'.rawurlencode($sku).'/status/archive',json_encode(array("is_archived"=>true)),$merchant_id,$status);
    			if($fullfillmentnodeid){
    				self::updateQtyOnJet($sku,0,$jetHelper,$fullfillmentnodeid,$merchant_id);
    			}
    			if($status==202){
    				$successArchive[]=$sku;
    			}
    		}
    	}
    	if(is_array($successArchive) && count($successArchive)>0)
    		$message.= "\nSku Successfully Archive On Jet: ".implode(', ',$successArchive)."\n";
    	$message.= "\n<!-----------------archiveProductOnJet function End------------------>\n";
    	return $successArchive;
    }
    public static function addVariant($product_id,$variantIds,$data,$images,$merchant_id,$changeType=false,$onWalmart=false,$onNewEgg=false)
    {
    	$queryVariant="INSERT INTO `jet_product_variants`(`option_id`, `product_id`, `merchant_id`, `option_title`, `option_sku`, `option_image`, `option_price`, `option_qty`,`variant_option1`, `variant_option2`, `variant_option3`,`option_unique_id`, `option_weight`,`status`)VALUES";
    	/*if($onWalmart)
    		$queryWalmartVariant="INSERT INTO `walmart_product_variants`(`option_id`, `product_id`, `merchant_id`,`status`) VALUES";*/
    	$countVar=0;
    	$variants=$data['variants'];
    	if($changeType){
    		//create attr_ids for variants products
    		//add attribute
			$options = $data['options'];
			$attrId = array();
			$attr_id="";
			foreach($options as $key=>$val)
			{
				$attrname = $val['name'];
				$attrId[$val['id']] = $val['name'];
				foreach ($val['values'] as $k => $v) 
				{
					$option_value[$attrname][$k] = $v;
				}
			}
			$attr_id = json_encode($attrId);
    	}
    	$skus=[];
    	foreach ($variants as $value) 
    	{
    		if(in_array($value['id'], $variantIds))
    		{
    			if($value['sku'] == "" || !self::validateSku($value['sku'],$data['id'],$merchant_id) || in_array($value['sku'], $skus)){
						continue;
				}
				$skus[] = $value['sku'];
    			$countVar++;
    			$option_weight=0;
    			$option_variant1 = isset($value['option1'])?$value['option1']:'';
				$option_variant2 = isset($value['option2'])?$value['option2']:'';
				$option_variant3 = isset($value['option3'])?$value['option3']:'';
				if(self::validateBarcode($value['barcode']))
					$barcode=$value['barcode'];
				else
					$barcode="";
				if($value['weight']>0)
					$option_weight =(float)Jetappdetails::convertWeight($value['weight'],$value['weight_unit']);
				$option_image_url = "";
				if(is_array($images) && count($images)>0)
				{
					foreach ($images as $value2)
					{
					 	if(isset($value['image_id']) && $value2['id'] == $value['image_id']){
					 		$option_image_url=$value2['src'];
					 	}
					}
				}
                $queryVariant.='(
									"'.$value['id'].'","'.$value['product_id'].'",
									"'.$merchant_id.'","'.addslashes($value['title']).'",
									"'.addslashes($value['sku']).'","'.addslashes($option_image_url).'",
									"'.(float)$value['price'].'","'.(int)$value['inventory_quantity'].'",
									"'.addslashes($option_variant1).'","'.addslashes($option_variant2).'",
									"'.addslashes($option_variant3).'",
									"'.$barcode.'","'.$option_weight.'","Not Uploaded"
								),';
				
    		}
    	}
    	$queryVariant=rtrim($queryVariant,',');
    	
        if($countVar>0)
            Data::sqlRecords($queryVariant);
        if($changeType)
        {
        	$query="UPDATE `jet_product` SET type='variants',attr_ids='".addslashes($attr_id)."' WHERE id='".$product_id."'";
  			Data::sqlRecords($query);
        }
    }
    public static function addNewVariants($data,$result,$product_id,$jetHelper,$merchant_id,$fullfillmentnodeid,$onWalmart=false,$onNewEgg=false)
    {
    	
    	//retire old product data on walmart/newegg
    	//self::sendProductRequestToMarketplace($product_id,$result,"delete",false,$onWalmart,$onNewEgg,$merchant_id);
    	//archive skus
    	$message="";
    	$message.="new variants in"."\n";
    	$archiveSkus=array();
    	if($jetHelper){
    		$modelProVar = Data::sqlRecords('SELECT `option_sku` from `jet_product_variants` where product_id="'.$product_id.'"','all','select');
	    	if(is_array($modelProVar) && count($modelProVar)>0)
	    	{
	    		foreach($modelProVar as $value)
	    		{
	    			if(self::checkSkuOnJet($value['option_sku'],$jetHelper,$merchant_id)==true)
	    				$archiveSkus[]=$value['option_sku'];
	    		}
	    	}
    	}
    	if(is_array($archiveSkus)&&count($archiveSkus)>0)
    		$message.=self::archiveProductOnJet($archiveSkus,$jetHelper,$merchant_id,$fullfillmentnodeid);
    	Data::sqlRecords('DELETE FROM `jet_product` WHERE merchant_id="'.$merchant_id.'" AND  id="'.$product_id.'"');
    	Data::sqlRecords('DELETE FROM `jet_product_variants` WHERE merchant_id="'.$merchant_id.'" AND  product_id="'.$product_id.'"');
    	$customData = JetProductInfo::getConfigSettings($merchant_id);
        $import_status = (isset($customData['import_status']) && $customData['import_status'])?$customData['import_status']:""; 
    	self::saveNewRecords($data,$merchant_id,$import_status);
    	$message.= "\n<!--------------------addNewVariants function End---------------------->\n";
    	return $message;
    }
    public static function checkSkuOnJet($sku,$jetHelper,$merchant_id,$param=false)
    {
    	$response="";
    	$status=true;
    	$response = $jetHelper->CGetRequest('/merchant-skus/'.rawurlencode($sku),$merchant_id,$status);
    	$responsearray=[];
    	$responsearray=json_decode($response,true);
    	if($status==200 && is_array($responsearray) && count($responsearray)>0){
    		if($param)
    			return $responsearray;
    		return true;
    	}
    	else
    		return false;
    }

    public static function saveNewRecords($data, $merchant_id, $import_status = false, $connection = false, $jetRegistartion = false)
    {
    	$proResult="";
    	try
    	{
    		$response = [];
    		$importLimit =  Data::getProductImportLimit($merchant_id);
            $importedCount = Data::getProductImportedCount($merchant_id);
	    	if(isset($data['id']) && ($importedCount<$importLimit) )
	    	{
	    		$product_images = "";
	    		$images = [];
	    		/*$customData = JetProductInfo::getConfigSettings($merchant_id);
	    		$import_status = (isset($customData['import_status']) && $customData['import_status'])?$customData['import_status']:"";*/
	    		if ($import_status=="published" && is_null($data['published_at'])) 
	    		{    			    		
    				self::insertImportErrorProduct($data['id'],$data['title'],'hidden_product',$merchant_id);
	    			$response['error']="hidden_product";
	    			return $response;
				}
	    		if($data['product_type']=="")
	    		{
	    			//save product info in product_import_error table
	    			self::insertImportErrorProduct($data['id'],$data['title'],'product_type',$merchant_id);
	    			
	    			$response['error']="product_type";
	    			return $response;
	    		}

	    		if(isset($data['images']))
	    			$images = $data['images'];
	    		$product_id = $data['id'];
	    		$imagArr = [];
	    		if(is_array($images) && count($images))
	    		{
		    		foreach ($images as $valImg)
		    		{
		    			$imagArr[]=$valImg['src'];
		    		}
		    		$product_images = implode(',',$imagArr);
	    		}

	    		$countVariants=0;
	    		$skus=$variantData=[];

	    		/*if(count($data['variants'])>1)
	    		{*/
				foreach ($data['variants'] as $value)
				{
					if($value['sku'] == "" || !self::validateSku($value['sku'],$data['id'],$merchant_id) || in_array($value['sku'], $skus)){
						continue;
					}
					$skus[] = $value['sku'];
					$option_weight = $option_price = 0.00;
					$option_price = (float)$value['price'];
					$option_variant1 = isset($value['option1'])?$value['option1']:'';
					$option_variant2 = isset($value['option2'])?$value['option2']:'';
					$option_variant3 = isset($value['option3'])?$value['option3']:'';
					if($value['weight']>0)
						$option_weight =(float)Jetappdetails::convertWeight($value['weight'],$value['weight_unit']);
					$option_image_url = "";
					foreach ($images as $value2){
					 	if(isset($value['image_id']) && $value2['id'] == $value['image_id']){
					 		$option_image_url=$value2['src'];
					 	}
					}
					
	                $countVariants++;
	                $variantData[$value['id']]['product_id']=$value['product_id'];
	                $variantData[$value['id']]['title']=addslashes($value['title']);
	                $variantData[$value['id']]['sku']=addslashes($value['sku']);
	                $variantData[$value['id']]['image']=addslashes($option_image_url);
	                $variantData[$value['id']]['price']=(float)$option_price;
	                $variantData[$value['id']]['qty']=(int)$value['inventory_quantity'];
	                $variantData[$value['id']]['variant_option1']=addslashes($option_variant1);
	                $variantData[$value['id']]['variant_option2']=addslashes($option_variant2);
	                $variantData[$value['id']]['variant_option3']=addslashes($option_variant3);
	                if(self::validateBarcode($value['barcode']))
	                	$variantData[$value['id']]['barcode']=$value['barcode'];
	                else
	                	$variantData[$value['id']]['barcode']='';
	                $variantData[$value['id']]['weight']=$option_weight;
				}
				//check product if all product having no skus and skip product to create
				if($countVariants==0)
				{
					self::insertImportErrorProduct($data['id'],$data['title'],'sku',$merchant_id);
	    			$response['error']="sku";
	    			return $response;
				}
				//add attribute
				$options = $data['options'];
				$attrId = [];
				foreach($options as $key=>$val)
				{
					$attrname = $val['name'];
					$attrId[$val['id']] = $val['name'];
					foreach ($val['values'] as $k => $v) 
					{
						$option_value[$attrname][$k] = $v;
					}
				}
				$attr_id = json_encode($attrId);
	    		//}
	    		//save attribute values for simple products
	    		
	    		$walmart_new_product_flag = $new_product_flag = false;
	    		$type="variants";
	    		if($countVariants==1)
					$type="simple";

	    		if(is_array($variantData) && count($variantData)>0)
	    		{
	    			$i=0;
	    			foreach ($variantData as $key => $val) 
	    			{
	    				//save data in jet_product 
	    				if($i==0)
	    				{
	    					$proResult = Data::sqlRecords("SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  id='".$product_id."' LIMIT 0,1","one","select");
	    					$response['success']=true;
			    			if(!isset($proResult['id']))
			    			{
			    				$descNew = "";
			    				$descNew = preg_replace("/<script.*?\/script>/", "", $data['body_html'])? : $data['body_html'];

			    				  // if (mb_detect_encoding($descNew) !== 'UTF-8') {
             //                        $descNew = utf8_encode($descNew);
             //                    }
                                
			    				$sql='INSERT INTO `jet_product`
									(
			    						`id`,`merchant_id`,
			    						`title`,`sku`,
			    						`type`,`description`,
			    						`image`,`price`,
										`qty`,`attr_ids`,
			    						`upc`,`status`,
			    						`vendor`,`variant_id`,
			    						`product_type`,`weight`
									)
									VALUES
									(
										"'.$product_id.'","'.$merchant_id.'",
										"'.addslashes($data['title']).'","'.addslashes($val['sku']).'",
										"'.$type.'","'.addslashes(utf8_encode($descNew)).'",
										"'.addslashes($product_images).'","'.(float)$val['price'].'",
										"'.(int)$val['qty'].'","'.addslashes($attr_id).'",
										"'.$val['barcode'].'","Not Uploaded",
										"'.addslashes($data['vendor']).'","'.$key.'",
										"'.addslashes($data['product_type']).'","'.$val['weight'].'"
									)';
			    		 		Data::sqlRecords($sql);
			    		 		$new_product_flag=true;
			    			}
			    			$proDetailsResult = Data::sqlRecords("SELECT `id` FROM `jet_product_details` WHERE merchant_id='".$merchant_id."' AND product_id='".$product_id."' LIMIT 0,1","one","select");
			    			if(!isset($proDetailsResult['id']))
			    			{
			    				$sql='INSERT INTO `jet_product_details`
									(
										`product_id`, 
										`merchant_id`
									)
									VALUES
									(
										"'.$product_id.'",
										"'.$merchant_id.'"
									)';

								Data::sqlRecords($sql);
			    			}
	    				}
	    				$i++;
		    			if($countVariants>1)
		    			{
		    				//save data in jet_product_variants
				    		$proVarresult = Data::sqlRecords("SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  option_id='".$key."' LIMIT 0,1","one","select");
				 			if(!isset($proVarresult['option_id']))
				 			{
				 				$sql = 'INSERT INTO `jet_product_variants`(
									`option_id`,`product_id`,
									`merchant_id`,`option_title`,
									`option_sku`,`option_image`,
									`option_price`,`option_qty`,
									`variant_option1`,`variant_option2`,
									`variant_option3`,`vendor`,
									`option_unique_id`,`option_weight`,`status`
								)VALUES(
									"'.$key.'","'.$product_id.'",
									"'.$merchant_id.'","'.addslashes($val['title']).'",
									"'.addslashes($val['sku']).'","'.addslashes($val['image']).'",
									"'.(float)$val['price'].'","'.(int)$val['qty'].'",
									"'.addslashes($val['variant_option1']).'","'.addslashes($val['variant_option2']).'",
									"'.addslashes($val['variant_option3']).'","'.addslashes($data['vendor']).'",
									"'.$val['barcode'].'","'.$val['weight'].'","Not Uploaded"
								)';
							 	Data::sqlRecords($sql);
				 			}
		    			}	
	    			}
	    			
		    		if(isset($data['product_type']) && $data['product_type'] !="")
			    	{
		    		 	$modelmap="";
		    		 	$query="";
		    		 	$queryObj="";
		    		 	$query='SELECT category_id FROM `jet_category_map` where merchant_id="'.$merchant_id.'" AND product_type="'.addslashes($data['product_type']).'" LIMIT 0,1';
		    		 	$modelmap = Data::sqlRecords($query,"one","select");

		    		 	if(isset($modelmap['category_id']) && $modelmap['category_id'])
				 		{
				 			$updateResult="";
				 			$query='UPDATE `jet_product` SET fulfillment_node="'.$modelmap['category_id'].'" where merchant_id="'.$merchant_id.'" AND  id="'.$data['id'].'"';
				 			Data::sqlRecords($query);
		    		 	}
		    		 	else
		    		 	{
		    		 		$queryObj="";
		    		 		$query='INSERT INTO `jet_category_map`(`merchant_id`,`product_type`)VALUES("'.$merchant_id.'","'.addslashes($data['product_type']).'")';
		    		 		Data::sqlRecords($query);
		    		 	}
		    		}
		    		
		    		//send data to walmart
		    		//$url = Yii::getAlias('@webwalmarturl')."/webhookupdate/product-create?debug";

                    //self::sendCurlRequest(['product_id'=>$product_id,'data'=>$data,'merchant_id'=>$merchant_id],$url);
	    		}

	    		//delete if product successfully saved but exit in product import error
				$checkExistProduct=Data::sqlRecords("SELECT `id` FROM `jet_product` WHERE id='".$data['id']."' LIMIT 0,1","one","select");
				if(isset($checkExistProduct['id']))
				{
					$query="DELETE FROM `product_import_error` WHERE merchant_id='".$merchant_id."' AND  id='".$checkExistProduct['id']."'";
					Data::sqlRecords($query);
				}
	    	}
	    	unset($data,$images,$imagArr,$attrId,$options,$result);
	    	return $response;
    	}
    	catch(\yii\db\Exception $e)
    	{
    		$message= "product result:".print_r($proResult,true).PHP_EOL;
			self::getInstance()->createExceptionLog('actionSaveNewRecords',$message.PHP_EOL.$e->getMessage(),$merchant_id);
			exit(0);
		}
		catch(Exception $e)
		{
			self::getInstance()->createExceptionLog('actionSaveNewRecords',$e->getMessage(),$merchant_id);
			exit(0);
		}
    	
    }
    public static function getInstance()
    {
    	return new Jetproductinfo();
    }
    public static function checkProductOptionBarcodeOnUpdate($option_array=[],$variant_array=[],$variant_id="",$barcode_type="",$product_upc="",$product_id="",$product_sku="")
    {
    		$merchant_id=MERCHANT_ID;
            $return_array=array();
            $return_array['success']=true;
            $return_array['error_msg']="";
            $variant_upc=$variant_sku="";
            $err_msg="";
            $variant_upc=trim($variant_array['option_unique_id']);
            $variant_sku=trim($variant_array['optionsku']);
            $match_skus_array=array();
            $matched_flag=false;
            $db_matched_flag=false;
            $parent_matched_flag=false;
            $variant_as_parent=0;
            
            if($variant_sku==trim($product_sku)){
                $variant_as_parent=1;
            }
            //echo $variant_as_parent.'|'.$variant_id.'|'.$barcode_type.'|'.$product_upc.'|'.$product_id.'|'.$product_sku;die("BVn");
            $barcode_types=["UPC","ISBN-10","ISBN-13","GTIN-14","EAN"];
            foreach($option_array as $option_id=>$option_attributes){
                if(trim($option_attributes['optionsku'])!=$variant_sku)
                {
                    if($variant_upc==trim($option_attributes['option_unique_id']) && in_array(trim($barcode_type),$barcode_types))
                    {
                        $match_skus_array[]=trim($option_attributes['optionsku']);
                        $matched_flag=true;
                    }
                }
            }
            if($variant_as_parent!=1 && $product_upc==$variant_upc){
                $matched_flag=true;
                $parent_matched_flag=true;
            }
            if(!$matched_flag){
                $matched_flag=$db_matched_flag=self::checkUpcVariants($variant_upc,$product_id,$variant_id,$variant_as_parent,$merchant_id);
            }
            if($matched_flag){
                    if(count($match_skus_array)>0){
                            $err_msg="Entered Barcode matched with Option Sku(s) : ".implode(' , ',$match_skus_array);
                    }
                    if($parent_matched_flag){
                        if($err_msg==""){
                            $err_msg="Entered Barcode matched with its Main Product";
                        }else{
                            $err_msg .=" & with its Main Product";
                        }
                    }
                    if($db_matched_flag){
                        $err_msg="Entered Barcode already exists";
                    }
                    $err_msg.=".Please enter unique Barcode.";
                    $return_array['success']=false;
                    $return_array['error_msg']=$err_msg;
            }

            return array($return_array['success'],$return_array['error_msg']);
    }

    public static function checkProductOptionAsinOnUpdate($option_array=array(),$variant_array=array(),$variant_id="",$product_asin="",$product_id="",$product_sku="",$product_collection=array(),$variant_collection=array())
    {
		$return_array=array();
        $merchant_id=MERCHANT_ID;
        $return_array['success']=true;
        $return_array['error_msg']="";
        $variant_asin=$variant_sku=$err_msg="";
        $variant_asin=trim($variant_array['asin']);
        $variant_sku=trim($variant_array['optionsku']);
        $match_skus_array=array();
        $matched_flag=$db_matched_flag=$parent_matched_flag=false;
        $variant_as_parent=0;
        if($variant_sku==trim($product_sku)){
            $variant_as_parent=1;
        }
        foreach($option_array as $option_id=>$option_attributes)
        {
            if(trim($option_attributes['optionsku'])!=$variant_sku)
            {
                if($variant_asin==trim($option_attributes['asin'])){
                    $match_skus_array[]=trim($option_attributes['optionsku']);
                    $matched_flag=true;
                }
            }
        }
        if($variant_as_parent!=1 && $product_asin==$variant_asin){
            $matched_flag=$parent_matched_flag=true;
        }
        if(!$matched_flag){
            $matched_flag=$db_matched_flag=self::checkAsinVariants($variant_asin,$product_id,$variant_id,$variant_as_parent,$merchant_id);
        }
        if($matched_flag)
        {
            if(count($match_skus_array)>0){
                $err_msg="Entered ASIN matched with Option Sku(s) : ".implode(' , ',$match_skus_array);
            }
            if($parent_matched_flag)
            {
                if($err_msg==""){
                    $err_msg="Entered ASIN matched with its Main Product";
                }else{
                    $err_msg .=" & with its Main Product";
                }
            }
            if($db_matched_flag){
                $err_msg="Entered ASIN already exists";
            }
            $err_msg.=".Please enter unique ASIN.";
            $return_array['success']=false;
            $return_array['error_msg']=$err_msg;
        }
        return array($return_array['success'],$return_array['error_msg']);
    } 
    public static function checkProductOptionMpnOnUpdate($option_array=array(),$variant_array=array(),$variant_id="",$product_mpn="",$product_id="",$product_sku="",$product_collection=array(),$variant_collection=array())
    {
        $return_array=array();
        $merchant_id=MERCHANT_ID;
        $return_array['success']=true;
        $return_array['error_msg']="";
        $variant_mpn="";
        $variant_sku="";
        $err_msg="";
        $variant_mpn=trim($variant_array['mpn']);
        $variant_sku=trim($variant_array['optionsku']);
        $match_skus_array=array();
        $matched_flag=false;
        $db_matched_flag=false;
        $parent_matched_flag=false;
        $variant_as_parent=0;
        if($variant_sku==trim($product_sku)){
            $variant_as_parent=1;
        }
        foreach($option_array as $option_id=>$option_attributes){
            if(trim($option_attributes['optionsku'])!=$variant_sku){
                if($variant_mpn==trim($option_attributes['mpn'])){
                    $match_skus_array[]=trim($option_attributes['optionsku']);
                    $matched_flag=true;
                }
            }
        }
        if($variant_as_parent!=1 && $product_mpn==$variant_mpn){
            $matched_flag=true;
            $parent_matched_flag=true;
        }
        if(!$matched_flag){
            $matched_flag=$db_matched_flag=self::checkAsinVariants($variant_mpn,$product_id,$variant_id,$variant_as_parent,$merchant_id);
        }
        if($matched_flag){
            if(count($match_skus_array)>0){
                $err_msg="Entered MPN matched with Option Sku(s) : ".implode(' , ',$match_skus_array);
            }
            if($parent_matched_flag){
                if($err_msg==""){
                    $err_msg="Entered MPN matched with its Main Product";
                }else{
                    $err_msg .=" & with its Main Product";
                }
            }
            if($db_matched_flag){
                $err_msg="Entered MPN already exists";
            }
            $err_msg.=".Please enter unique MPN.";
            $return_array['success']=false;
            $return_array['error_msg']=$err_msg;
        }
        return array($return_array['success'],$return_array['error_msg']);
    }
    public static function checkUpcType($product_upc){
        if(is_numeric($product_upc))
        {
            if(strlen($product_upc)==12)
                return "UPC";
            elseif(strlen($product_upc)==10)
                return "ISBN-10";
            elseif(strlen($product_upc)==13)
                return "EAN";
            elseif(strlen($product_upc)==14)
                return "GTIN-14";
        }
        return "";
    }
    public static function checkUpcSimple($product_upc="",$product_id="",$merchant_id="")
    {
    	$product_upc=trim($product_upc);
        $main_product_count = $variant_count = 0;
        $main_products = $variant = [];
        
        $query="SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  upc='".$product_upc."' AND id <> '".$product_id."' ";        
        $main_products = Data::sqlRecords($query,'all','select');
        $main_product_count=count($main_products);
        $queryObj="";
        $query1 = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  option_unique_id='".$product_upc."' ";
        $variant = Data::sqlRecords($query1,'all','select');
        $variant_count= count($variant);
        unset($variant);
        unset($main_products);
        if($main_product_count > 0 || $variant_count > 0){
                return true;
        }
        return false;
    }
    public static function checkUpcVariantSimple($product_upc="",$product_id="",$product_sku="",$merchant_id="")
    {
        $product_upc=trim($product_upc);
        $main_product_count = $variant_count = 0;
        $main_products = $variant = [];
        
        $query="SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  `upc`='".$product_upc."' AND `id`<>'".$product_id."' ";
        $main_products = Data::sqlRecords($query,'all','select');
        $main_product_count=count($main_products);
        unset($main_products);
        $queryObj = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  option_sku <> '".$product_sku."' AND option_unique_id='".$product_upc."' ";
        $variant = Data::sqlRecords($queryObj,'all','select');
        $variant_count= count($variant);
        unset($variant);
        if($main_product_count > 0 || $variant_count > 0){
                return true;
        }
        return false;
    }
    public static function checkUpcVariants($product_upc="",$product_id="",$variant_id="",$variant_as_parent=0,$merchant_id="")
    {
    	
        $variant_count=0;
        $main_product_count=0;
        $main_products=array();
        $variant=array();
        if($variant_as_parent){
        	$queryObj = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  upc='".trim($product_upc)."' AND id <> '".$product_id."' ";
        	$main_products = Data::sqlRecords($queryObj,'all','select');
        }else{
        	$queryObj = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  upc='".trim($product_upc)."' ";
        	$main_products = Data::sqlRecords($queryObj,'all','select');
        }
        $main_product_count=count($main_products);
        unset($main_products);
        $queryObj = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  option_unique_id='".trim($product_upc)."' AND option_id <>'".$variant_id."' ";
        $variant = Data::sqlRecords($queryObj,'all','select');
        $variant_count= count($variant);
        unset($variant);
        if($main_product_count > 0 || $variant_count > 0){
            return true;
        }
        return false;
    }

    public static function checkAsinSimple($product_asin="",$product_id="",$merchant_id="")
    {
    	$product_asin=trim($product_asin);
        $main_product_count = $variant_count = 0;
        $main_products = $variant = [];
        
        $queryObj = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  ASIN='".$product_asin."' AND id <> '".$product_id."' ";
        $main_products = Data::sqlRecords($queryObj,'all','select');
        $main_product_count=count($main_products);
        unset($main_products);
        $queryObj = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  asin='".$product_asin."' ";
        $variant = Data::sqlRecords($queryObj,'all','select');
        $variant_count= count($variant);
        unset($variant);
        if($main_product_count > 0 || $variant_count > 0){
            return true;
        }
        return false;
    }
    public static function checkAsinVariantSimple($product_asin="",$product_id="",$product_sku="",$merchant_id="")
    {
    	$product_asin=trim($product_asin);
        $main_product_count = $variant_count = 0;
        $main_products = $variant = [];
        
        $queryObj = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  ASIN='".$product_asin."' AND id <> '".$product_id."' ";
        $main_products = Data::sqlRecords($queryObj,'all','select');
        $main_product_count=count($main_products);
        unset($main_products);
        $queryObj = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  option_sku <> '".$product_sku."' AND asin='".$product_asin."' ";
        $variant = Data::sqlRecords($queryObj,'all','select');
        $variant_count= count($variant);
        unset($variant);
        if($main_product_count > 0 || $variant_count > 0){
            return true;
        }
        return false;
    }
    public static function checkAsinVariants($product_asin="",$product_id="",$variant_id="",$variant_as_parent=0,$merchant_id="")
    {    	
    	$product_asin=trim($product_asin);
        $variant_count=0;
        $main_product_count=0;
        $main_products=array();
        $variant=array();
        if($variant_as_parent){
        	$queryObj = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  ASIN='".trim($product_asin)."' AND id <> '".$product_id."'";
        	$main_products = Data::sqlRecords($queryObj,'all','select');
        }else{
        	$queryObj = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  ASIN='".trim($product_asin)."' ";
        	$main_products = Data::sqlRecords($queryObj,'all','select');
        }
        $main_product_count=count($main_products);
        unset($main_products);        
        $queryObj = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  asin='".trim($product_asin)."' AND option_id <> '".$variant_id."' ";
        $variant = Data::sqlRecords($queryObj,'all','select');
        $variant_count= count($variant);
        unset($variant);
  		if($variant_count>0 || $main_product_count>0){
            return true;
        }
        return false;
    }
    public static function checkMpnSimple($product_mpn="",$product_id="",$merchant_id="")
    {        
        $product_mpn=trim($product_mpn);
        $main_product_count = $variant_count = 0;
        $main_products = $variant = [];
        
        $sql = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  mpn='".addslashes($product_mpn)."' AND id <> '".$product_id."' ";
        $main_products = Data::sqlRecords($sql,'all','select');
        $main_product_count=count($main_products);
        unset($main_products);
        $sql1 = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  option_mpn='".addslashes($product_mpn)."' ";
        $variant = Data::sqlRecords($sql1,'all','select');
        $variant_count= count($variant);
        unset($variant);
        if($main_product_count > 0 || $variant_count > 0){
            return true;
        }
        return false;
    }
    public static function checkMpnVariantSimple($product_mpn="",$product_id="",$product_sku="",$merchant_id="")
    {        
        $product_mpn=trim($product_mpn);
        $main_product_count=0;
        $main_products = $variant = [];
        $variant_count=0;
        $sql1 = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  mpn='".addslashes($product_mpn)."' AND id <> '".$product_id."' ";
        $main_products = Data::sqlRecords($sql1,'all','select');
        $main_product_count=count($main_products);
        unset($main_products);
        $sql = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND  option_sku <> '".$product_sku."' AND option_mpn='".addslashes($product_mpn)."' ";
        $variant = Data::sqlRecords($sql,'all','select');
        $variant_count= count($variant);
        unset($variant);
        if($main_product_count > 0 || $variant_count > 0){
            return true;
        }
        return false;
    }
    public static function checkMpnVariants($product_mpn="",$product_id="",$variant_id="",$variant_as_parent=0,$merchant_id="")
    {        
        $product_mpn=trim($product_mpn);
        $variant_count = $main_product_count = 0;
        $main_products = $variant = [];
        if($variant_as_parent){            
            $sql1 = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  mpn='".trim($product_mpn)."' AND id <> '".$product_id."' ";
            $main_products = Data::sqlRecords($sql1,'all','select');
        }else{
            $sql = "SELECT `id` FROM `jet_product` WHERE merchant_id='".$merchant_id."' AND  mpn='".trim($product_mpn)."' ";
            $main_products = Data::sqlRecords($sql,'all','select');
        }
        $main_product_count=count($main_products);
        unset($main_products);
        $queryObj = "SELECT `option_id` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND option_mpn='".trim($product_mpn)."' and option_id <> '".$variant_id."' ";
        $variant = Data::sqlRecords($queryObj,'all','select');
        $variant_count= count($variant);
        unset($variant);
  		if($variant_count>0 || $main_product_count>0){
  		    return true;
  		}
  		return false;
    }
    public static function priceChange($price,$priceType,$changePrice)
    {
    	$updatePrice=0.00;
    	if($priceType=="percentageAmount"){
    		$updatePrice = (float)($price+($changePrice/100)*($price));    		
    	}
    	elseif($priceType=="fixedAmount")
    	{
    		$updatePrice=(float)($price + $changePrice);
    	}
    	$updatePrice = number_format($updatePrice, 2, '.', '');
    	return $updatePrice;
    }
        
    public static function sentCurlRequest($product)
    {
    	//set curl inventory request to walmart
    	$hostname = Yii::getAlias('@hostname').'/';
		$url = $hostname."integration/walmart/webhookupdate/productupdate";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($product));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_TIMEOUT,1);
		$result = curl_exec($ch);
		curl_close($ch);
    }

    public static function deleteVariant($id,$result,$jetHelper="",$merchant_id=14,$product=false,$onWalmart=false,$onNewEgg=false)
    {
    	//send delete request to walmart 
    	//self::sendProductRequestToMarketplace($id,$result,"delete",false,$onWalmart,$onNewEgg,$merchant_id);
    	$archiveFlag=true;
    	if($jetHelper)
    		$archiveFlag = self::archiveAllVariants($id,$jetHelper,$merchant_id);
    	if($archiveFlag)
    	{
    		$query="DELETE FROM `jet_product_variants` where merchant_id='".$merchant_id."' AND  product_id='".$id."'";
		    Data::sqlRecords($query,null,"delete");
		    if($product)
		    {
		    	$query="DELETE FROM `jet_product` where merchant_id='".$merchant_id."' AND  id='".$id."'";
		    	Data::sqlRecords($query,null,"delete");
		    }
		    else
		    {
		    	$query="UPDATE `jet_product` SET type='simple' WHERE merchant_id='".$merchant_id."' AND  id='".$id."'";
	  			Data::sqlRecords($query,null,"update");
		    }
    	}
    }
    public static function deleteProductById($id,$type,$onWalmart=false,$onNewEgg=false)
    {
    	if($type=="child")    	
    		$query="DELETE FROM `jet_product_variants` where option_id='".$id."'";    	
    	else    	
    		$query="DELETE FROM `jet_product` where id='".$id."'";
	    	    	
    	Data::sqlRecords($query,null,'delete');
    }
    public static function archiveAllVariants($id,$jetHelper=[],$merchant_id=null)
    {
    	$archiveSkus=[];
    	$successCountSkus=$skuCount=0;
    	$query="SELECT pro.sku,var.option_sku FROM `jet_product` as pro LEFT JOIN `jet_product_variants` as var ON pro.id='".$id."' AND pro.merchant_id='".$merchant_id."' AND  pro.id=var.product_id ";
    	$productVarColl=Data::sqlRecords($query,"all","select");
    	$skuCount=0;
    	if(is_array($productVarColl) && count($productVarColl)>0)
    	{
    		foreach ($productVarColl as $value) 
    		{
    			if($value['option_sku'])
    				$sku=$value['option_sku'];    			
    			else
    				$sku=$value['sku'];	
    			
    			if(self::checkSkuOnJet($sku,$jetHelper,$merchant_id)){
    				$archiveSkus[]=$sku;
    				$skuCount++;
    			}
    		}
    	}
    	if(is_array($archiveSkus) && count($archiveSkus)>0){
    		$successCountSkus=self::archiveProductOnJet($archiveSkus,$jetHelper,$merchant_id);
    	}
    	if($successCountSkus==$skuCount){
    		return true;
    	}
    	return false;
    }

    public static function getConfigSettings($merchant_id)
    {    	
    	$config = $jetConfig = [];
    	$jetConfig = Data::sqlRecords('SELECT `data`,`value` from `jet_config` where merchant_id="'.$merchant_id.'"','all','select');
    	if (is_array($jetConfig) && count($jetConfig)>0)
    	{
			foreach ($jetConfig as $configValue) {
				$config[$configValue['data']] = $configValue['value'];
			}			
		}
		return $config;
    }

    public static function getPriceToBeUpdatedOnJet($id, $merchant_id, $price, $configSetting, $type)
    {
		if(isset($configSetting['set_price_amount']) && $configSetting['set_price_amount']!='') 
		{
			$priceFormat = explode('-', $configSetting['set_price_amount']);
			if(is_array($priceFormat) && count($priceFormat)==2) 
			{
				$price = floatval($price);
				$format = $priceFormat[0];
				$value = floatval($priceFormat[1]);
				$price = self::priceChange($price, $format, $value);
			}
		} 
		else 
		{
			if($type=="simple")
			{
				$priceColl=Data::sqlRecords("SELECT `update_price` FROM `jet_product_details` WHERE merchant_id='".$merchant_id."' AND `product_id`='".$id."' LIMIT 0,1","one","select");
				if($priceColl['update_price'])
					$price = $priceColl['update_price'];
			}
			else{
				$priceColl=Data::sqlRecords("SELECT `update_option_price` FROM `jet_product_variants` WHERE merchant_id='".$merchant_id."' AND `option_id`='".$id."' LIMIT 0,1","one","select");
				if($priceColl['update_option_price'])
					$price = $priceColl['update_option_price'];
			}	
		}
		return $price;
    }
	public static function getProductInventory($offset=0,$limit=5000,$handle='')
    {
        $modelApp = new Jetappdetails();
        $api_host="https://merchant-api.jet.com/api";
        $query="select jet.id,jet.merchant_id,wal.status as wal_status,jet.status as jet_status from `jet_product` jet LEFT JOIN `walmart_product` wal ON (jet.status='Under Jet Review' or jet.status='Available for Purchase') or wal.status='PUBLISHED' AND  jet.id=wal.product_id limit ".$offset.",".$limit;

        $totalProduColl=Data::sqlRecords($query,'all','select');       
        $merchantArray=[];
        
        foreach ($totalProduColl as $key => $value) 
        {
            $merchant_id=$value['merchant_id'];
            $product_id=$value['id'];
			$isWalmart=false;
			$sc="";
            if(!array_key_exists($merchant_id,$merchantArray))
            {
            	fwrite($handle, PHP_EOL.PHP_EOL."Merchant ".$merchant_id.PHP_EOL);
	            $isValidate=$modelApp->isValidateapp($merchant_id);
	            if($isValidate!="expire" && $isValidate!="not purchase")
	            {
               		$query="select username,auth_key,api_user,api_password,fullfilment_node_id from `user` INNER JOIN `jet_configuration` ON user.id='".$merchant_id."' AND user.id=jet_configuration.merchant_id INNER JOIN `jet_shop_details` jet_shop ON jet_shop.install_status=1 AND (jet_shop.purchase_status='Purchased' OR jet_shop.purchase_status='Not Purchase' )  AND (user.id = jet_shop.merchant_id) LIMIT 0,1";
               		$jetConfigColl=Data::sqlRecords($query,'one','select');
               		if(is_array($jetConfigColl) && count($jetConfigColl)>0 && $modelApp->appstatus($jetConfigColl['auth_key']))
               		{
               			fwrite($handle, PHP_EOL."Merchant with jet config details ".PHP_EOL);
               			/*$merchantArray[$merchant_id]['token']=$jetConfigColl['auth_key'];
               			$merchantArray[$merchant_id]['username']=$jetConfigColl['username'];*/
               			$sc = new ShopifyClientHelper($jetConfigColl['username'],$jetConfigColl['auth_key'],PUBLIC_KEY,PRIVATE_KEY);
               			$merchantArray[$merchant_id]['jet_config']= $jetConfigColl;
               		}
               		else
               		{
               			fwrite($handle, PHP_EOL."Merchant with missing jet api details ".PHP_EOL);
               			$isWalmart=true; // why & how this
               		}	
	            }
	            else
	            {
	            	fwrite($handle, PHP_EOL."Merchant with trial-expire or unistall ".PHP_EOL);
	            	$isWalmart=true; // why & how this
	            }
	            
	            if($sc)
	            	$merchantArray[$merchant_id]['shopify_obj']=$sc;
            }
            if(isset($merchantArray[$merchant_id]['shopify_obj']))
            {
            	$shopifyObject=$merchantArray[$merchant_id]['shopify_obj'];
            	$productColl = $shopifyObject->call('GET', '/admin/products/'.$product_id.'.json');
	            if(!isset($productColl['errors']))
	            {
	                fwrite($handle, "Product-".$product_id.PHP_EOL);
	                $fullfilment_node_id="";
	                $jetHelper="";
	                if(array_key_exists('jet_config', $merchantArray[$merchant_id]))
	                {
	                	$jetHelper=new Jetapimerchant($api_host,$merchantArray[$merchant_id]['jet_config']['api_user'],$merchantArray[$merchant_id]['jet_config']['api_password']);
	                	$fullfilment_node_id=$merchantArray[$merchant_id]['jet_config']['fullfilment_node_id'];
	                }
	                self::updateInventoryOnJet($productColl,$jetHelper,$merchant_id,$fullfilment_node_id,$handle);
	            } 
            }               
        }
    }
    public static function updateInventoryOnJet($product=[],$jetHelper="",$merchant_id='',$fullfillmentnodeid="",$handle='')
    {
        if(isset($product['id']))
        {
            $product_id=$product['id'];
            $product_sku=$product['variants'][0]['sku'];
            $isVariant=false;
            $product_qty=$product['variants'][0]['inventory_quantity'];
            if(count($product['variants'])>1)
            {
                fwrite($handle, PHP_EOL."----Product variant option start----".PHP_EOL);
                $isVariant=true;
                foreach($product['variants'] as $value)
                {
                    $option_id=$value['id'];
                    $option_sku=$value['sku'];
                    $option_qty=$value['inventory_quantity'];
                    $sql="UPDATE `jet_product_variants` SET `option_qty`='".$option_qty."' WHERE merchant_id='".$merchant_id."' AND `option_id`='".$option_id."'";
                    Data::sqlRecords($sql,null,'update');
                    fwrite($handle,"sku: ".$option_sku." ,inventory: ".$option_qty.PHP_EOL);
                    if($jetHelper && $option_qty<=15)
                    {
                    	fwrite($handle,"sku: ".$option_sku." ,inventory less or equal to 15: ".$option_qty.PHP_EOL);
                    	$inventoryUpdateRes=self::updateQtyOnJet($option_sku,$option_qty,$jetHelper,$fullfillmentnodeid,$merchant_id);  
                    	fwrite($handle,$inventoryUpdateRes.PHP_EOL);
                    }
                    //set curl inventory request to walmart
                    $url = Yii::getAlias('@webwalmarturl')."/webhookupdate/productupdate";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['inventory'=>$option_qty,'sku'=>$option_sku,'type'=>'inventory','merchant_id'=>$merchant_id]));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
                    curl_setopt($ch, CURLOPT_TIMEOUT,1);
                    $result = curl_exec($ch);
                    curl_close($ch);
                }
                fwrite($handle, PHP_EOL."----Product variant option end----".PHP_EOL);
            }
            $sql="UPDATE `jet_product` SET `qty`='".$product_qty."' WHERE merchant_id='".$merchant_id."' AND  `id`='".$product_id."'";
            Data::sqlRecords($sql,null,'update');    
            fwrite($handle,"sku: ".$product_sku." ,inventory: ".$product_qty.PHP_EOL);   
            if($jetHelper && $option_qty<=15)  
            {
            	fwrite($handle,"sku: ".$product_sku." ,inventory less or equal to 15: ".$product_qty.PHP_EOL);   
            	$inventoryUpdateRes=self::updateQtyOnJet($product_sku,$product_qty,$jetHelper,$fullfillmentnodeid,$merchant_id);
            	fwrite($handle,$inventoryUpdateRes.PHP_EOL);
            }         
            //set curl inventory request to walmart
            $url = Yii::getAlias('@webwalmarturl')."/webhookupdate/productupdate";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['inventory'=>$product_qty,'sku'=>$product_sku,'type'=>'inventory','merchant_id'=>$merchant_id]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_TIMEOUT,1);
            $result = curl_exec($ch);
            curl_close($ch);
        }    
    }
    public static function changeProductStatus($statusResponse=[],$merchant_id=14){
    	$count=0;
    	if(is_array($statusResponse) && count($statusResponse)>0)
    	{
    		foreach ($statusResponse as $key => $value) 
    		{
    			$count++;
    			$query="UPDATE `jet_product` SET `status`='".addslashes($value['status'])."' WHERE merchant_id='".$merchant_id."' and sku='".addslashes($key)."'";
    			Data::sqlRecords($query,null,'update');
    		}
    	}
    	return $count;
    }

    public static function validateSku($sku, $productId, $merchant_id=null)
	{        
        /*$query = "SELECT `jp`.`id`,`jpv`.`option_id` FROM `jet_product` `jp` LEFT JOIN `jet_product_variants` `jpv` ON `jp`.`id`=`jpv`.`product_id` WHERE `jp`.`merchant_id`='{$merchant_id}' AND (`jp`.`sku`='{$sku}' OR `jpv`.`option_sku`='{$sku}')";*/
        $query = "SELECT `result`.* FROM (SELECT `sku` , `id` AS `product_id` , `variant_id` AS `option_id` ,`merchant_id`, `type` FROM `jet_product` WHERE `merchant_id`='{$merchant_id}' AND `sku`='".addslashes($sku)."' UNION SELECT `option_sku` AS `sku` , `product_id` , `option_id`, `merchant_id`, 'variants' AS `type` FROM `jet_product_variants` WHERE `merchant_id`='{$merchant_id}' AND `option_sku`='".addslashes($sku)."') as `result`";
        $result = Data::sqlRecords($query, 'one', 'select');
        if($result)
        {
            if($result['product_id']==$productId || $result['option_id']==$productId) {
                return true;
            }
            return false;
        }
        else
            return true;
    }

    public static function validateBarcode($barcode)
    {
    	$upcLen = strlen($barcode);
    	if($barcode!="" && is_numeric($barcode) && ($upcLen==12 || $upcLen==10 || $upcLen==13 || $upcLen==14)){
    		return true;
    	}
    	return false;
    }
    public static function sendProductRequestToMarketplace($id,$result,$type=false,$deleteIds=false,$onWalmart=false,$onNewEgg=false,$merchant_id)
    {
    	//set curl inventory request to walmart/Newegg
    	$product=['id'=>$id,'product_collection'=>$result,'query_type'=>$type,'data'=>$deleteIds,'merchant_id'=>$merchant_id];
    	//if($onWalmart)
    	//{
    		$url = Yii::getAlias('@webwalmarturl')."/webhookupdate/product-delete";
    		//$url = "http://192.168.0.222/integration/walmart/webhookupdate/product-delete";
			self::sendCurlRequest($product,$url);
    	//}
		
    }
    public static function sendProductUpdateRequestToMarketplace($id,$result,$updateData=false,$onWalmart=false,$onNewEgg=false,$merchant_id)
    {
    	//set curl inventory request to walmart/Newegg
    	$product=['id'=>$id,'product_collection'=>$result,'data'=>$updateData,'merchant_id'=>$merchant_id];
    	//if($onWalmart)
    	//{
    	$url = Yii::getAlias('@webwalmarturl')."/webhookupdate/product-update1";
    		//$url = "http://192.168.0.222/integration/walmart/webhookupdate/product-update";
		self::sendCurlRequest($product,$url);
    	//}	
    }
    public static function sendCurlRequest($data=[],$url="")
    {
    	
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_TIMEOUT,1);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
    }
    public static function deleteUnpublishedproduct($product_id,$merchant_id)
    {
    	$sql = "DELETE FROM `jet_product` WHERE `merchant_id`='{$merchant_id}' AND  `id`='{$product_id}' ";
    	$sql1 = "DELETE FROM `jet_product_variants` WHERE  `merchant_id`='{$merchant_id}' AND  `product_id`='{$product_id}' ";
    	Data::sqlRecords($sql,null,'delete');
    	Data::sqlRecords($sql1,null,'delete');
    }

    /* 
     * function for creating log 
     */
    public function createExceptionLog($functionName,$msg,$shopName = 'common')
    {
        $dir = \Yii::getAlias('@webroot').'/var/jet/exceptions/'.$functionName.'/'.$shopName;
        if (!file_exists($dir)){
            mkdir($dir,0775, true);
        }
        $filenameOrig = $dir.'/'.time().'.txt';
        $handle = fopen($filenameOrig,'a');
        $msg = date('d-m-Y H:i:s')."\n".$msg;
        fwrite($handle,$msg);
        fclose($handle);
        $this->sendEmail($filenameOrig,$msg);
    }

    /**
     * function for sending mail with attachment
     */
    public static function sendEmail($file,$msg,$email = 'shivamverma@cedcoss.com')
    {
       try
       {
            $name = 'Shopify jet Cedcommerce';
        
            $EmailTo = $email.',kshitijverma@cedcoss.com';
            $EmailFrom = $email;
            $EmailSubject = "Shopify-Jet Exception Log" ;
            $from ='Shopify-jet Cedcommerce';
            $message = $msg;
            $separator = md5(time());

            // carriage return type (we use a PHP end of line constant)
            $eol = PHP_EOL;

            // attachment name
            $filename = 'exception';//store that zip file in ur root directory
            $attachment = chunk_split(base64_encode(file_get_contents($file)));

            // main header
            $headers  = "From: ".$from.$eol;
            $headers .= "MIME-Version: 1.0".$eol; 
            $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

            // no more headers after this, we start the body! //

            $body = "--".$separator.$eol;
            $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol.$eol;
            $body .= $message.$eol;

            // message
            $body .= "--".$separator.$eol;
            /*  $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
            $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
            $body .= $message.$eol; */

            // attachment
            $body .= "--".$separator.$eol;
            $body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
            $body .= "Content-Transfer-Encoding: base64".$eol;
            $body .= "Content-Disposition: attachment".$eol.$eol;
            $body .= $attachment.$eol;
            $body .= "--".$separator."--";

            // send message
            if (mail($EmailTo, $EmailSubject, $body, $headers)) {
                $mail_sent = true;
            } else {
                $mail_sent = false;
            }
        }
        catch(Exception $e)
        {
            
        }
    }
    
    
   /* public static function getMarketplacePrice($proData=[],$jetHelper,$merchant_id)
    {
    	$rawSkuDetails=[];
    	if(is_array($proData) && count($proData)>0)
    	{
    		foreach ($proData as $value) {
    			$rawDetails = $jetHelper->CGetRequest('/merchant-skus/'.$value['sku'].'/salesdata',$merchant_id);
    			$salesDataCollection=json_decode($rawDetails,true);
    			if(isset($salesDataCollection['my_best_offer']))
    			{
    				$rawSkuDetails[$value['sku']]['my_price']=$salesDataCollection['my_best_offer'][0]['shipping_price']+$salesDataCollection['my_best_offer'][0]['item_price'];
    				$rawSkuDetails[$value['sku']]['marketplace_price']=$salesDataCollection['best_marketplace_offer'][0]['shipping_price']+$salesDataCollection['best_marketplace_offer'][0]['item_price'];
    				if($rawSkuDetails[$value['sku']]['my_price']>$rawSkuDetails[$value['sku']]['marketplace_price'])
    					$rawSkuDetails[$value['sku']]['type']="increase";
    				else
    					$rawSkuDetails[$value['sku']]['type']="equal";
    			}
    		}
    	}
    	return $rawSkuDetails;
    }*/

    public static function validateAsin($asin)
    {
    	if($asin!="" && strlen($asin)==10 && ctype_alnum($asin))
    	{
    		return true;
    	}
    	return false;
    }

    public static function deleteProductType($product_type,$merchant_id)
    {
    	$productData=Data::sqlRecords("SELECT count(*) count FROM `jet_product` WHERE  merchant_id='".$merchant_id."' AND  `product_type`='".addslashes($product_type)."' LIMIT 0,1","one","select");
    	if(isset($productData['count']) && $productData['count']==0)
    	{
    		Data::sqlRecords("DELETE FROM `jet_category_map` WHERE  merchant_id='".$merchant_id."' AND `product_type`='".addslashes($product_type)."' ");
    		return "product type deleted";
    	}
    	return false;
    }
    
    // Importing new Product type in app
    public static function updateProductType($data, $merchant_id)
    {
    	try
    	{
    		if(isset($data['id']))
    		{
    			if(isset($data['product_type']) && $data['product_type'] !="")
				{
					$modelmap = [];
					
					$query='SELECT category_id FROM `jet_category_map` where merchant_id="'.$merchant_id.'" AND product_type="'.addslashes($data['product_type']).'" LIMIT 0,1';
					$modelmap = Data::sqlRecords($query,"one","select");

					if( !empty($modelmap) && isset($modelmap['category_id']))
					{
						$updateResult="";
						$query='UPDATE `jet_product` SET `fulfillment_node`="'.$modelmap['category_id'].'" where `merchant_id`="{$merchant_id}" AND product_type="'.addslashes($data['product_type']).'" ';
						Data::sqlRecords($query,null,'update');
					}
					else
					{
						$res = 0;
						$query='INSERT INTO `jet_category_map`(`merchant_id`,`product_type`)VALUES("'.$merchant_id.'","'.addslashes($data['product_type']).'")';
						$res = Data::sqlRecords($query,null,'insert');
					}
				} 
				if ($res)
					$response['success']="1";
    		}
    	}    	
    	catch(Exception $e)
    	{
    		self::getInstance()->createExceptionLog('actionproduct type import',$e->getMessage(),$merchant_id);
    		exit(0);
    	}
    }
}	
?>
