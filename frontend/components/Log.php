<?php
/**
 *  Created by Amit Shukla.
 *  User: cedcoss
 *  Date: 23/4/19 11:56 AM
 *
 */

namespace frontend\components;

use Yii;
//use frontend\components\DateTime;

class Log
{
    public static $modules = [
        'walmart'   => 1,
        'jet'       => 2,
        'newegg'    => 3,
        'sears'     => 4,
        'neweggca'  => 5,
        'bonanza'   => 6,
        'tophatter' => 7,
        'pricefalls'=> 8,
        'wish'      => 9,
        'fruugo'    => 10,
        'bestbuyca' => 11,
        'walmartca' => 12,
        'etsy'      => 13,
        'rakutenus' => 14,
        'reverb'    => 15,
        'catchmp'   => 16,
        'rakutenfr' => 17,
    ];
	
	public static function canCreateErrorExceptionLog()
	{
		return true;
	}
	
	public static function isLoggingEnabled()
	{
		return true;
	}
	
	public static function isQueueLogEnabled()
	{
		return true;
	}
	
	public static function isLogTraceEnabled($module=null)
	{
		if(is_null($module)) {
			return true;
		}
		elseif(!is_numeric($module)) {
			if(isset(static::$modules[$module])) {
				$module = static::$modules[$module];
			} else {
				return false;
			}
		}
		
		$flag = false;
		
		switch ($module) {
			case 1:
				$flag = true;
				break;
			
			case 2:
				$flag = true;
				break;
			
			case 3:
				$flag = true;
				break;
			
			case 4:
				$flag = true;
				break;
			
			case 5:
				$flag = true;
				break;
			
			case 6:
				$flag = true;
				break;
			
			case 7:
				$flag = true;
				break;
			
			case 8:
				$flag = true;
				break;
			
			case 9:
				$flag = true;
				break;
			
			case 10:
				$flag = true;
				break;
			
			case 11:
				$flag = true;
				break;
			
			case 12:
				$flag = true;
				break;
			
			case 13:
				$flag = true;
				break;
			
			case 14:
				$flag = true;
				break;
			case 15:
				$flag = true;
				break;
			
			case 16:
				$flag = true;
				break;
			
			case 17:
				$flag = true;
				break;
		}
		
		return $flag;
	}
	
	public static function getWebhookTracePath($module, $process_names='product/create', $merchant, $log_name='trace',$is_webhook=false)
	{
        $date = self::getCustomCurrentDate();
        if ($is_webhook) //if(Yii::$app instanceof \yii\console\Application)
            $path = Yii::getAlias('@sqs-var')."/webhook-trace/{$process_names}/{$merchant}/{$log_name}/{$date}.log";
        else
            $path = Yii::getAlias('@rootdir')."/var/log/{$module}/{$process_names}/{$merchant}/{$log_name}/{$date}.log";
        return $path;
	}
	
	public static function createWebhookTrace($module, $process_names, $merchant, $log_name='trace', $message, $mode = 'a')
	{
		$logEnabled = self::isLogTraceEnabled($module);
		
		if($logEnabled)
		{
			$mask = @umask(000);
			$file_path = self::getWebhookTracePath($module, $process_names, $merchant, $log_name);
			$dir = dirname($file_path);
			
			if (!file_exists($dir)) {
				$result = @mkdir($dir, 0777, true);
				if(!$result) {
					@umask($mask);
					return false;
				}
			}
			
			/*$dt = new DateTime('Asia/Calcutta', 'd-m-Y H:i:s');
			$log_time = $dt->getDateTime();*/
            $log_time = Yii::$app->datetime->getDateTime();
			
			$fileOrig = fopen($file_path, $mode);
			fwrite($fileOrig, "[$log_time]" . PHP_EOL . $message . PHP_EOL );
			fclose($fileOrig);
			
			@umask($mask);
		}
	}
	
	public static function createWebhookLog($module, $process_names, $merchant, $log_name='trace', $message, $mode = 'a')
	{
		$logEnabled = self::isLogTraceEnabled($module);
        
        $module = !is_null($module)?$module:'hook_raw';
        
		if($logEnabled)
		{
			$mask = @umask(000);
			$file_path = self::getWebhookTracePath($module, $process_names, $merchant, $log_name,true);
			$dir = dirname($file_path);
			
			if (!file_exists($dir)) {
				$result = @mkdir($dir, 0777, true);
				if(!$result) {
					@umask($mask);
					return false;
				}
			}
			
			/*$dt = new DateTime('Asia/Calcutta', 'd-m-Y H:i:s');
			$log_time = $dt->getDateTime();*/
            $log_time = Yii::$app->datetime->getDateTime();
			
			$fileOrig = fopen($file_path, $mode);
			fwrite($fileOrig, "[$log_time]" . PHP_EOL . $message . PHP_EOL );
			fclose($fileOrig);
			
			@umask($mask);
		}
	}
	
	public static function createLog($message, $path = 'rabbitmq.log', $mode = 'a', $trace = false, $force = false)
	{
		$logEnabled = self::isLoggingEnabled();
		
		if($logEnabled || $force)
		{
			$mask = @umask(000);
			
			$file_path = Yii::getAlias('@rootdir') ."/var/$path";
			$dir = dirname($file_path);
			if (!file_exists($dir)) {
				$result = @mkdir($dir, 0777, true);
				if(!$result) {
					@umask($mask);
					return false;
				}
			}
			
			if ($trace) {
				try {
					throw new \Exception($message);
				} catch (\Exception $e) {
					$message = $e->getTraceAsString();
				}
			}
			
			/*$dt = new DateTime('Asia/Calcutta', 'd-m-Y H:i:s');
			$log_time = $dt->getDateTime();*/
            $log_time = Yii::$app->datetime->getDateTime();
			
			$fileOrig = fopen($file_path, $mode);
			fwrite($fileOrig, "[$log_time]" . PHP_EOL . $message . PHP_EOL . PHP_EOL);
			fclose($fileOrig);
			
			@umask($mask);
		}
	}
	
	public static function createQueueProcessingLog($message, $path = 'queue-processing.log', $mode = 'a', $force = false)
	{
		$logEnabled = self::isQueueLogEnabled();
		
		if($logEnabled || $force)
		{
			$mask = @umask(000);
			
			$file_path = Yii::getAlias('@rootdir')."/queue-processing/{$path}";
			$dir = dirname($file_path);
			if (!file_exists($dir)) {
				$result = @mkdir($dir, 0777, true);
				if(!$result) {
					@umask($mask);
					return false;
				}
			}
			
			/*$dt = new DateTime('Asia/Calcutta', 'd-m-Y H:i:s');
			$log_time = $dt->getDateTime();*/
            $log_time = Yii::$app->datetime->getDateTime();
			
			$fileOrig = fopen($file_path, $mode);
			fwrite($fileOrig, "[$log_time]" . PHP_EOL . $message . PHP_EOL . PHP_EOL);
			fclose($fileOrig);
			
			@umask($mask);
		}
	}
	
	/**
	 * function for sending mail with attachment
	 */
	public static function sendEmail($message, $mailTo='himanshusahu@cedcoss.com', $file_attachment=null, $subject='Rabbitmq Exception Log')
	{
		try {
			$mailFrom = 'himanshusahu@cedcoss.com';
			$from = 'Cedcommerce';
			
			$separator = md5(time());
			
			// carriage return type (we use a PHP end of line constant)
			$eol = PHP_EOL;
			
			// main header
			$headers = "From: " . $from . $eol;
			$headers .= "MIME-Version: 1.0" . $eol;
			$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";
			
			// no more headers after this, we start the body! //
			
			$body = "--" . $separator . $eol;
			$body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol . $eol;
			$body .= $message . $eol;
			
			// message
			$body .= "--" . $separator . $eol;
			/*  $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
			$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
			$body .= $message.$eol; */
			
			if(!is_null($file_attachment))
			{
				// attachment name
				$filename = 'exception';//store that zip file in ur root directory
				$attachment = chunk_split(base64_encode(file_get_contents($file_attachment)));
				
				// attachment
				$body .= "--" . $separator . $eol;
				$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
				$body .= "Content-Transfer-Encoding: base64" . $eol;
				$body .= "Content-Disposition: attachment" . $eol . $eol;
				$body .= $attachment . $eol;
				$body .= "--" . $separator . "--";
			}
			
			// send message
			// if (mail($mailTo, $subject, $body, $headers)) {
			//     $mail_sent = true;
			// } else {
			//     $mail_sent = false;
			// }
			
			// return $mail_sent;
			return true;
		} catch (\Exception $e) {
			$error = 'Exception During error&exception mail : ' . $e->getMessage(). PHP_EOL . $e->getTraceAsString();
			self::createLog($error, 'errors-exceptions/'.time().'.log', 'a', false, true);
			
			return false;
		}
	}
	
    public static function getCustomCurrentDate()
    {
        Yii::$app->datetime->setFormat('d-m-Y');
        return Yii::$app->datetime->getDateTime();
	}

	/**
	 * @param string $path : path with filename, file created in under var folder.
	 * @param array $csvdata : Data to be write in CSV
	 * @param string $mode : Mode in which you want to write CSV file
	 */
	public function createCSV($path, $csvdata = [], $mode = 'a')
	{
		if (is_null($path)) {
			throw new \Exception("Path cannot be blank");
		}
		$mask = @umask(000);

		$filePath = \Yii::getAlias('@rootdir') . DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . $path;
		$directory_path = dirname($filePath);
		if (!is_dir($directory_path)) {
			$response = mkdir($directory_path, 0777, true);
			if (!$response) {
				throw new \Exception("Directory not created");
			}
		}
		$file = fopen($filePath, $mode);
		if (!empty($csvdata)) {
			foreach ($csvdata as $data) {
				fputcsv($file, $data);
			}
		}
		fclose($file);
		@umask($mask);
	}
}
