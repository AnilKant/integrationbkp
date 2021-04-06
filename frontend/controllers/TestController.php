<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\modules\rabbitmq\components\RQueueHelper;
use frontend\modules\Base\components\Framework\App;

/**
* Test controller
*/
class TestController extends Controller 
{
    public function beforeAction($action)
    {
        var_dump(Yii::getVersion());die('ddd');
        Yii::$app->controller->enableCsrfValidation = false;
        return true;
    }
    public function actionIndex() 
    {
    	var_dump(Yii::getAlias('@webroot'));
    	var_dump(Yii::getAlias('@rootdir'));die;
        echo "cron service runnning";
    }
    
    public function actionProcess($queueName,$appName)
    {
        $rqueueHelper = new RQueueHelper();
        
        if($queueName == RQueueHelper::WEBHOOK_QUEUE)
        {
            $class = '\frontend\modules\rabbitmq\components\QueueHandler\WebookQueueHandler';
        }
        elseif(in_array($queueName, $rqueueHelper->getProductQueue()))
        {
            $class = '\frontend\modules\rabbitmq\components\QueueHandler\CommonHandler\ProductHandler';
        }
        elseif(in_array($queueName, $rqueueHelper->getOrderQueue()))
        {
            $class = '\frontend\modules\rabbitmq\components\QueueHandler\CommonHandler\OrderHandler';
        }
        elseif(in_array($queueName, $rqueueHelper->getUninstallQueue()))
        {
            $class = '\frontend\modules\rabbitmq\components\QueueHandler\CommonHandler\ShopHandler';
            $msg = file_get_contents(__DIR__.'/../json-data/uninstall.json');
        }
        elseif(in_array($queueName, $rqueueHelper->getBlockedQueue()))
        {
            $class = '\frontend\modules\rabbitmq\components\QueueHandler\FailedQueueHandler';
        }
        else
        {
            die('Unknown Queue.');
        }
        
        switch ($queueName){
            case 'oldapps_uninstall_app' :
                $msg = file_get_contents(__DIR__.'/../json-data/uninstall.json');
                break;
            case 'oldapps_order_update' :
                $msg = file_get_contents(__DIR__.'/../json-data/order-update.json');
                break;
            case 'oldapps_order_cancel' :
                $msg = file_get_contents(__DIR__.'/../json-data/order-cancel.json');
                break;
            default:
                die("Handler sample data not found!");
                break;
        }
        
        $queue = App::getObjectManager()->get($class);
        $arr = $queue->getQueues($queueName);
        $processor = $arr[$queueName][$appName];
       
        $obj = App::getObjectManager()->get($processor['classname']);
        $method = $processor['method'];
        $response = $obj->$method($msg);
        echo "<hr><pre>";
        var_dump($response);
        die("<hr>handler-response");
    }
}