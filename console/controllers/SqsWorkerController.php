<?php
/**
 *  Created by Amit Shukla.
 *  User: cedcoss
 *  Date: 01/10/19, 6:57 PM
 *
 */

/**
 *  Created by Amit Shukla.
 *  User: cedcoss
 *  Date: 20/09/19, 7:14 PM
 *
 */

namespace console\controllers;

use yii\console\Controller;
use frontend\modules\sqs\components\RQueueHelper;
use frontend\modules\Base\components\Framework\App;

class SqsWorkerController extends Controller
{
    public function actionProcess($queueName='',$appsType='oldapps')
    {
        if($queueName)
        {
            $rqueueHelper = new RQueueHelper($appsType);
            
            if($queueName == $rqueueHelper->webhook_queue)
            {
                $class = '\frontend\modules\sqs\components\QueueHandler\WebookQueueHandler';
            }
            elseif(in_array($queueName, $rqueueHelper->getProductQueue()))
            {
                $class = '\frontend\modules\sqs\components\QueueHandler\CommonHandler\ProductHandler';
            }
            elseif(in_array($queueName, $rqueueHelper->getOrderQueue()))
            {
                $class = '\frontend\modules\sqs\components\QueueHandler\CommonHandler\OrderHandler';
            }
            elseif(in_array($queueName, $rqueueHelper->getShopUpdateQueue()))
            {
                $class = '\frontend\modules\sqs\components\QueueHandler\CommonHandler\ShopHandler';
            }
            elseif(in_array($queueName, $rqueueHelper->getBlockedQueue()))
            {
                $class = '\frontend\modules\sqs\components\QueueHandler\FailedQueueHandler';
            } 
            else 
            {
                die('Unknown Queue.');
            }
            
            $queue = App::getObjectManager()->get($class,[$rqueueHelper]);
            $queue->process($queueName,true);
        }
        else
        {
            die('No QueueName Given.');
        }
    }
}