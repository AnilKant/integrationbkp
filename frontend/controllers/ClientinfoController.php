<?php
namespace frontend\controllers;
use frontend\modules\walmart\components\Data;
use yii\helpers\BaseJson;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class ClientinfoController extends Controller
{
    public function beforeAction($action)
    {
        if(isset(getallheaders()['Action.NAME'])=='Combo Payment')
        {
            return true;
        }else{
            return false;
        }
        
    }
    public function actionWalmartclientdetail()
    {
        if(!empty($_POST['shop']))
        {
            $shop_name = $_POST['shop'];
            $query = "SELECT `merchant_id` FROM `walmart_shop_details` WHERE `shop_url`='".$shop_name."' AND `status`='1'";
            $shopData = Data::sqlRecords($query,'one','select');
            if(!empty($shopData['merchant_id']))
            {
                $return = ['success'=>true];
                print_r(json_encode($return));
                exit(0);
            }else{
                $return = ['success'=>false];
                print_r(json_encode($return));
                exit(0);
            }
        }
    }
    public function actionWalmartpaymentdetail()
    {
        if(!empty($_POST['charge_id']))
        {
            $chargeId = $_POST['charge_id'];
            $query = "SELECT `merchant_id` FROM `walmart_recurring_payment` WHERE `id`='".$chargeId."' AND `status`='active'";
            $shopData = Data::sqlRecords($query,'one','select');
            if(!empty($shopData['merchant_id']))
            {
                $return = ['success'=>true];
                print_r(json_encode($return));
                exit(0);
            }else{
                $return = ['success'=>false];
                print_r(json_encode($return));
                exit(0);
            }
        }
    }

    public function actionNeweggusclientdetail()
    {
        if(!empty($_POST['shop']))
        {
            $shop_name = $_POST['shop'];
            $query = "SELECT `merchant_id` FROM `newegg_shop_detail` WHERE `shop_url`='".$shop_name."' AND `install_status`='1'";
            $shopData = Data::sqlRecords($query,'one','select');
            if(!empty($shopData['merchant_id']))
            {
                $return = ['success'=>true];
                print_r(json_encode($return));
                exit(0);
            }else{
                $return = ['success'=>false];
                print_r(json_encode($return));
                exit(0);
            }
        }
    }
    public function actionNewegguspaymentdetail()
    {
        if(!empty($_POST['charge_id']))
        {
            $chargeId = $_POST['charge_id'];
            $query = "SELECT `merchant_id` FROM `newegg_payment` WHERE `id`='".$chargeId."' AND `status`='active'";
            $shopData = Data::sqlRecords($query,'one','select');
            if(!empty($shopData['merchant_id']))
            {
                $return = ['success'=>true];
                print_r(json_encode($return));
                exit(0);
            }else{
                $return = ['success'=>false];
                print_r(json_encode($return));
                exit(0);
            }
        }
    }
    public function actionNeweggcaclientdetail()

    {
        if(!empty($_POST['shop']))
        {
            $shop_name = $_POST['shop'];
            $query = "SELECT `merchant_id` FROM `newegg_can_shop_detail` WHERE `shop_url`='".$shop_name."' AND `install_status`='1'";
            $shopData = Data::sqlRecords($query,'one','select');
            if(!empty($shopData['merchant_id']))
            {

                $return = ['success'=>true];
                print_r(json_encode($return));
                exit(0);
            }else{
                $return = ['success'=>false];
                print_r(json_encode($return));
                exit(0);
            }
        }
    }
    public function actionNeweggcapaymentdetail()
    {
        if(!empty($_POST['charge_id']))
        {
            $chargeId = $_POST['charge_id'];
            $query = "SELECT `merchant_id` FROM `newegg_can_payment` WHERE `id`='".$chargeId."' AND `status`='active'";

            $shopData = Data::sqlRecords($query,'one','select');
            if(!empty($shopData['merchant_id']))
            {
                $return = ['success'=>true];
                print_r(json_encode($return));
                exit(0);
            }else{
                $return = ['success'=>false];
                print_r(json_encode($return));
                exit(0);
            }
        }

    }    
}
