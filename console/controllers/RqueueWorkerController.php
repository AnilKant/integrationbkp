<?php
namespace console\controllers;

use yii\console\Controller;
use frontend\modules\rabbitmq\components\RQueueHelper;
use frontend\modules\Base\components\Framework\App;

class RqueueWorkerController extends Controller
{
    public function actionProcess($queueName='') 
    {
        if($queueName)
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
            }
            elseif(in_array($queueName, $rqueueHelper->getBlockedQueue()))
            {
                $class = '\frontend\modules\rabbitmq\components\QueueHandler\FailedQueueHandler';
            } 
            else 
            {
                die('Unknown Queue.');
            }

            $queue = App::getObjectManager()->get($class);
            $queue->process($queueName);
        }
        else
        {
            die('No QueueName Given.');
        }
    }
}