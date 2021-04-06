<?php

namespace frontend\controllers;

use frontend\components\ShopifyClientHelper;
use frontend\modules\jet\components\Jetappdetails;
use frontend\modules\neweggmarketplace\components\Neweggappdetail;
use frontend\modules\walmart\components\Walmartappdetails;
use frontend\modules\sears\components\Searsappdetails;
use frontend\modules\neweggcanada\components\Neweggappdetail as Neweggcaappdetail;
use Yii;
use frontend\modules\jet\components\Data;
use frontend\modules\jet\components\Sendmail;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    const WALMART_APP = 'walmart';
    const JET_APP = 'jet';
    const NEWEGG_APP = 'newegg';
    const NEWEGGCA_APP = 'neweggca';
    const SEARS_APP = 'sears';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'savedetails') {
            $this->enableCsrfValidation = false;
        }
        return true;
    }

    public function actionIndex()
    {


        return $this->redirect(Yii::getAlias('@hostname'));
        /*$this->layout='blank';
        $urls=[];
        if(false && !\Yii::$app->user->isGuest) 
        {
            $merchant_id=Yii::$app->user->identity->id;
            $urls = Data::checkInstalledApp($merchant_id);
        }
        else
        {
            $urls['jet']['url']=Yii::getAlias('@webjeturl');
            $urls['walmart']['url']=Yii::getAlias('@webwalmarturl');
            $urls['newegg']['url']=Yii::getAlias('@webneweggurl');
            $urls['sears']['url']=Yii::getAlias('@websearsurl');

        }
        $urls['bonanza']['url']=Yii::getAlias('@webbonanzaurl');
        $urls['tophatter']['url']=Yii::getAlias('@webtophatterurl');
        $urls['pricefalls']['url']=Yii::getAlias('@webpricefallsurl');
        $urls['fruugo']['url']=Yii::getAlias('@webfruugourl');

        return $this->render('index',['urls'=>$urls]);*/
    }

    /**
     * page not found
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            die;
            return $this->render('error', ['exception' => $exception]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSavedetails()
    {
        Yii::$app->controller->enableCsrfValidation = false;
        $data = Yii::$app->request->post();
        if (isset($data['firstname'], $data['emailid'], $data['comment'], $data['g-recaptcha-response']) && $data['firstname'] && $data['emailid'] && $data['comment'] && $data['g-recaptcha-response']) {
            $query = "INSERT INTO `upcoming_clients` (`name`, `email`, `description`) VALUES ('" . addslashes($_POST['firstname']) . "', '" . addslashes($_POST['emailid']) . "', '" . addslashes($_POST['comment']) . "');";
            Data::sqlRecords($query, null, 'insert');
            Sendmail::contactUs($data);
            return json_encode(['success' => true, "message" => "Thank you for submitting your details, our team member will contact you very soon"]);
        }

        return json_encode(['success' => false, "message" => "Please fill required details"]);
    }

    public function actionOffers()
    {
        $this->layout = 'blank';
        return $this->render('offers');
    }

    public function actionInventoryItem()
    {
        return $this->render('updateinventoryitem');
    }

    public function actionUpdateInventoryItem()
    {
        $currentPage = Yii::$app->request->post('currentpage');

        $limit = 50;
        $offset = $currentPage * $limit;
        //        $query = "SELECT `product_id`,`merchant_id` FROM ((SELECT `product_id`,`jet_product`.`merchant_id` FROM `jet_product` WHERE `jet_product`.`type`='simple' ) UNION (SELECT `jet_product_variants`.`option_id` AS `variant_id`,merchant_id FROM `jet_product_variants` )) as `merged_data` ORDER BY merchant_id ASC LIMIT $offset,$limit";
        $query = "SELECT `id`,`merchant_id` FROM `jet_product` ORDER BY merchant_id ASC LIMIT $offset,$limit";

        $result = Data::sqlRecords($query, 'all');
        $productArray = [];
        $error = [];
        $success = [];
        $error_msg = ' NULL ';
        $success_msg = ' NULL ';
        if ($result) {

            foreach ($result as $item) {
                $productArray[$item['merchant_id']][] = $item['id'];
            }

            if (!empty($productArray)) {
                foreach ($productArray as $merchantId => $productId) {

                    $response = $this->getShopifyObject($merchantId);
                    if (isset($response['success'])) {
                        $sc = $response['object'];
                        $products = $sc->call('GET', '/admin/products.json?ids=' . implode(',', $productId), array());

                        if (isset($products['errors'])) {
                            $error[] = $merchantId . ' => ' . $products['errors'];
                            continue;
                        }
                        $when_product = '';
                        $when_product_variant = '';
                        $parent_variant_ids = [];
                        $child_variant_ids = [];
                        foreach ($products as $product) {
                            $variantData = $product['variants'];

                            foreach ($variantData as $variant) {
                                $parent_variant_ids[] = $variant['id'];
                                $when_product .= ' WHEN ' . $variant['id'] . ' THEN ' . '"' . $variant['inventory_item_id'] . '"';
                                if (count($variantData) > 1) {
                                    $child_variant_ids[] = $variant['id'];
                                    $when_product_variant .= ' WHEN ' . $variant['id'] . ' THEN ' . '"' . $variant['inventory_item_id'] . '"';
                                }
                            }
                        }

                        $parent_ids = implode(',', $parent_variant_ids);
                        $child_ids = implode(',', $child_variant_ids);

                        if (!empty($parent_ids)) {
                            $query1 = "UPDATE `jet_product` SET  
                                    `inventory_item_id` = CASE `variant_id`
                                    " . $when_product . " 
                                END
                                WHERE variant_id IN (" . $parent_ids . ") AND merchant_id=" . $merchantId;

                            Data::sqlRecords($query1, null, 'update');
                        }

                        if (!empty($child_ids)) {
                            $query2 = "UPDATE `jet_product_variants` SET  
                                    `inventory_item_id` = CASE `option_id`
                                    " . $when_product_variant . " 
                                END
                                WHERE option_id IN (" . $child_ids . ") AND merchant_id=" . $merchantId;

                            Data::sqlRecords($query2, null, 'update');
                        }

                        $success[] = $merchantId . ' => Inventory Item Id Updated Successfully';
                    } else {
                        $error[] = $response['message'];
                    }
                }

                if (count($error) > 0) {
                    $error_msg = json_encode($error);
                }

                if (count($success) > 0) {
                    $success_msg = json_encode($success);
                }

                $returnArr = ['success' => true, 'message' => 'success message => ' . $success_msg . ' AND error message => ' . $error_msg, 'nextpage' => ($currentPage + 1)];
            } else {
                $returnArr = ['error' => true, 'message' => 'empty product array', 'nextpage' => 0];
            }
        } else {
            $returnArr = ['error' => true, 'message' => 'No Products Found [all products are updated]', 'nextpage' => 0];
        }
        return json_encode($returnArr);
    }

    /**
     * @param $shop_name
     * @param $marketplace
     * @return array
     */
    public function getShopifyObject($merchantId)
    {
        $merchantData = Data::sqlRecords("SELECT shop_name FROM merchant_db WHERE merchant_id=" . $merchantId, 'one');
        if (!$merchantData) {
            return ['error' => true, 'message' => 'merchant not exist in merchant_db'];
        }
        $shop_name = $merchantData['shop_name'];

        $installFlag = false;
        $install_on_walmart = false;
        $install_on_jet = false;
        $install_on_neweggca = false;
        $install_on_newegg = false;
        $install_on_sears = false;
        $sc = '';
        $marketplaceList = [
            'jet' => 'jet_shop_details',
            'walmart' => 'walmart_shop_details',
            'newegg' => 'newegg_shop_detail',
            'neweggca' => 'newegg_can_shop_details',
            'sears' => 'sears_shop_details'
        ];

        foreach ($marketplaceList as $marketplace_name => $table_name) {

            switch ($marketplace_name) {
                case self::WALMART_APP:
                    $app_status = Walmartappdetails::appstatus($shop_name);
                    if ($app_status) {
                        $token = $this->getTableData($merchantId, 'walmart_shop_details');
                        $sc = new ShopifyClientHelper($shop_name, $token, WALMART_APP_KEY, WALMART_APP_SECRET);
                        $install_on_walmart = true;
                    }
                    break;
                case self::NEWEGG_APP:
                    $app_status = Neweggappdetail::appstatus($shop_name);
                    if ($app_status) {
                        $token = $this->getTableData($merchantId, 'newegg_shop_detail');
                        $sc = new ShopifyClientHelper($shop_name, $token, NEWEGG_APP_KEY, NEWEGG_APP_SECRET);
                        $install_on_newegg = true;
                    }
                    break;
                case self::JET_APP:
                    $app_status = Jetappdetails::appstatus($shop_name);
                    if ($app_status) {
                        $token = $this->getTableData($merchantId, 'user');
                        $sc = new ShopifyClientHelper($shop_name, $token, PUBLIC_KEY, PRIVATE_KEY);
                        $install_on_jet = true;
                    }
                    break;
                case self::NEWEGGCA_APP:
                    $app_status = Neweggcaappdetail::appstatus($shop_name);
                    if ($app_status) {
                        $token = $this->getTableData($merchantId, 'newegg_can_shop_detail');
                        $sc = new ShopifyClientHelper($shop_name, $token, NEWEGGCANADA_APP_KEY, NEWEGGCANADA_APP_SECRET);
                        $install_on_neweggca = true;
                    }
                    break;
                case self::SEARS_APP:
                    $app_status = Searsappdetails::appstatus($shop_name);
                    if ($app_status) {
                        $token = $this->getTableData($merchantId, 'sears_shop_details');
                        $sc = new ShopifyClientHelper($shop_name, $token, SEARS_APP_KEY, SEARS_APP_SECRET);
                        $install_on_sears = true;
                    }
                    break;
            }
        }

        if ($install_on_jet || $install_on_newegg || $install_on_neweggca || $install_on_sears || $install_on_walmart) {

            return ['success' => true, 'object' => $sc];
        } else {
            return ['error' => true, 'message' => 'merchant not install on any app'];
        }
    }

    public function getTableData($merchant_id, $table_name)
    {
        if ($table_name == 'user') {
            $query = "SELECT auth_key FROM {$table_name} WHERE id='" . $merchant_id . "' LIMIT 0,1";
            $shopData = Data::sqlRecords($query, "one", "select");
        } else {
            $query = "SELECT token FROM {$table_name} WHERE merchant_id='" . $merchant_id . "' LIMIT 0,1";
            $shopData = Data::sqlRecords($query, "one", "select");
        }

        return $shopData['token'];
    }
}
