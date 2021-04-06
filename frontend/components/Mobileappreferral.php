<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 31/10/17
 * Time: 10:03 AM
 */

namespace frontend\components;

use yii\base\Component;
use yii\base\Exception;

class Mobileappreferral extends Component
{
    public static function validatappreferalcode($appref_code)
    {
        // $url = Yii::getAlias('@webbaseurl')."/shopifywebhook/curlprocessforproductupdate?maintenanceprocess=1";
        $data = [];

        $url = 'https://apps-referral.cedcommerce.com/index.php/api/validate?appref=' . $appref_code;

        $respone = Data::sendCurlRequest($data, $url);
        $res = json_decode($respone, true);
        if ($res && $res['success'] && $res['status']) {
            return true;
        } elseif ($res && $res['success'] && !$res['status']) {
            return false;
        } else {
            return false;
        }
    }

    public static function sendDatatoMobileapp($data)
    {
        $url = 'https://apps-referral.cedcommerce.com/index.php/api/reffershop';
        $respone = Data::sendCurlRequest($data, $url);
        $res = json_decode($respone, true);

        return true;
    }
}