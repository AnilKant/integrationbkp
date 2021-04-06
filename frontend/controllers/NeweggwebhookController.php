<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use frontend\modules\neweggmarketplace\components\Sendmail;
use frontend\modules\neweggmarketplace\components\Data;
use frontend\modules\neweggmarketplace\models\NeweggShopDetail;

class NeweggwebhookController extends Controller
{

    public function beforeAction($action)
    {
        if ($this->action->id == 'isinstall') {
            Yii::$app->controller->enableCsrfValidation = false;
        }
        return true;
    }

    public function actionIsinstall()
    {
        $webhook_content = '';
        $webhook = fopen('php://input', 'rb');
        while (!feof($webhook)) { //loop through the input stream while the end of file is not reached
            $webhook_content .= fread($webhook, 4096); //append the content on the current iteration
        }
        fclose($webhook); //close the resource
        $data = "";
        $data = $webhook_content;
        if ($webhook_content == '' || empty(json_decode($data, true))) {
            return;
        }
        $data = json_decode($webhook_content, true); //convert the json to array
        $data['shopName'] = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : "common";
        $path = \Yii::getAlias('@webroot') . '/var/newegg/uninstall/' . date('d-m-Y') . '/' . $data['shopName'];
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
        $file_path = $path . '/data.log';
        $myfile = fopen($file_path, "a+");
        fwrite($myfile, "\n[" . date('d-m-Y H:i:s') . "]\n");
        fwrite($myfile, print_r($data, true));
        fclose($myfile);

        $url = Yii::getAlias('@webbaseurl') . "/neweggwebhook/curlprocessforuninstall";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function actionCurlprocessforuninstall()
    {

        $data = $_POST;
        $shop = "";
        $model = "";
        $model1 = "";
        $modelnew = "";

        $path = \Yii::getAlias('@webroot') . '/var/newegg/uninstall/' . date('d-m-Y') . '/' . $data['shopName'];
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
        $file_path = $path . '/data.log';
        $myfile = fopen($file_path, "a+");
        // fwrite($myfile, "\n[".date('d-m-Y H:i:s')."]\n");
        fwrite($myfile, print_r($data, true));
        //fclose($myfile);

        if ($data && isset($data['id'])) {
            $shop = $data['myshopify_domain'];
            $model = User::find()->where(['username' => $shop])->one();

            fwrite($myfile, print_r($model, true));
            fwrite($myfile, PHP_EOL . "SHOP NAME" . PHP_EOL);
            fwrite($myfile, $shop . PHP_EOL);

            if ($model) {
                fwrite($myfile, PHP_EOL . 'In if condition' . PHP_EOL);

                $extensionModel = "";
                $email_id = "";
                $extensionModel = NeweggShopDetail::find()->where(['LIKE', 'shop_url', $shop])->one();

                fwrite($myfile, PHP_EOL . 'Extension Detail' . PHP_EOL);
                fwrite($myfile, print_r($extensionModel, true));

                if ($extensionModel) {
                    $email_id = $extensionModel->email;
                    $extensionModel->app_status = "uninstall";
                    $extensionModel->uninstall_date = date('Y-m-d H:i:s');
                    $extensionModel->install_status = 0;
                    $extensionModel->save(false);

                    fwrite($myfile, PHP_EOL . 'Extension Detail After Save' . PHP_EOL);
                    fwrite($myfile, print_r($extensionModel, true));
                    Sendmail::uninstallmail($email_id);
                }
            }
        }
    }
}