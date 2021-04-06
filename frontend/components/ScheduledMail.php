<?php
namespace frontend\components;

use Yii;
use yii\base\Component;

class ScheduledMail extends component
{
	private $_data = null;
	public function __construct($data)
	{

        $data['domain'] = Yii::getAlias('@hostname').'/';

		$this->_data = $data;
		$this->_template = $template;
		$this->_debug = $debug;
		$this->insertTemplate($this->_template);
		$this->_source = $source;
		if($this->_debug)
		{
			$dir = Yii::getAlias('@webroot').'/var';
			if(!file_exists($dir)){
                mkdir($dir,0775, true);
            }
			$this->_handle = fopen($dir.'/mail.log','a');
			if(isset($this->_data['html_content']) && !empty($this->_data['html_content'])){
               $data['html_content'] = 'yes';
            }
			$this->log(print_r($data,true));
		}
	}

	public function setScheduledMail()
	{

	}

	public function getScheduledMail()
	{

	}

}