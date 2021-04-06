<?php
namespace backend\components;
use Yii;
use yii\base\Component;

class Sms extends component
{   
    private $_client = null;
    public $config;

    public function __construct(){
        global $config;
        $this->config = $config['components']['sms']['config'];
    }
    public function sendSms($to, $text) {
        try
        {
            $this->initClient();
            $message = $this->_client->account->messages->create(
                            $to,
                            array(
                                'from' => $this->config['from'],
                                'body' => $text
                            )
                        );
                return ['success' =>true,'message'=>$message->sid];
        }
        catch(\Twilio\Exceptions\RestException $e){
            return ['success' =>false,'message'=>$e->message];
        }
        
    }
    private function initClient()
    {
        if (!$this->_client) {
            if (!$this->config['sid']) {
                throw new InvalidConfigException('SID is required');
            }
            if (!$this->config['token']) {
                throw new InvalidConfigException('Token is required');
            }
            $this->_client = new \Twilio\Rest\Client($this->config['sid'], $this->config['token']);
        }
    }


}