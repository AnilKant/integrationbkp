<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use yii\base\Theme;
/**
* Worker controller
*/
class AutoController extends Controller 
{
    
    public $functions = ['setup'=>
    						['static-content'=>
    							['deploy'=>
    								[
    									'functionName'=>'contentDeploy',
    									'code'=>'setup:static-content:deploy',
    									'description'=>'Deploy static content from modules '
    								]
    							]
    						]
    					];

    public $basePath = '';
    public $themeBasePath = '';
    public function actionRun() 
    {
        global $argv;
        if(isset($argv[2]) && $argv['1']=='auto/run'){
        	if($argv[2]==''){
        		$this->listFunctions();
        	}
        	else
        	{
        		$this->callExactFunction($argv[2]);	
        	}	
        }
        else
        {
        	$this->listFunctions();
        }
       
        
        
    }

    public function callExactFunction($command){
    	$commands = explode(':',$command);
    	$arr = $this->functions;
    	foreach($commands as $command){
    		if(isset($arr[$command]['functionName'])){
    			$arr = $arr[$command]['functionName'];
    			break;
    		}
    		$arr = $arr[$command];
    	}
    	if(is_string($arr)){
    		$this->$arr();
    	}else
    	{
    		$this->listFunctions();
    	}
    }
    public function contentDeploy(){
    	global $config;
    	echo PHP_EOL.'Content Deploy Started.'.PHP_EOL;
    	$base_dir = dirname(__FILE__, 3);

    	$required_dir = $base_dir.'/frontend/modules';
    	$files = scandir($required_dir);
    	$themeBasePath = $config['components']['view']['theme']['basePath'];
    	$this->basePath = dirname(Yii::getAlias('@app'));
    	$this->themeBasePath = str_replace('@app',$this->basePath.'/frontend',$themeBasePath);
        foreach($files as $key => $value){

            $path = $required_dir.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'assets';
          
            if($path && $value != "." && $value != ".." && file_exists($path))
           		$this->DirContents($path, $this->themeBasePath);
        }	
    	echo PHP_EOL.'Done'.PHP_EOL;
    }
    public function listFunctions(){
    	
    	$this->showAllFunctions($this->functions);

    	echo PHP_EOL;
    }
    
    public function showAllFunctions($functions){
    	foreach($functions as $data){
    		if(isset($data['functionName']))
    		{
    			echo PHP_EOL.'php yii auto/run '.$data['code'].'  '.$data['description'];
    		}
    		else
	    	{
	    		$this->showAllFunctions($data);
	    	}
    	}

    }

    protected function DirContents($dir, $base_dir, $filter = '', &$results = array()) {

        $files = scandir($dir);
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

            if(!is_dir($path)) {
                if(strpos($path,'.php') !== false)
                    continue;
                
            	$themeFile = str_replace($this->basePath.'/frontend', $this->themeBasePath, $path);
            	if(!file_exists($themeFile))
            	{
            		$themeFile = $path;
            	}

            	$filePath = str_replace($this->basePath.'/frontend/', '', $path);
            	$filePath = $this->basePath.'/frontend/web/static/'.$filePath;
            	if(!file_exists(dirname($filePath))){
            		mkdir(dirname($filePath),0755,true);
            	}
            	copy($themeFile,$filePath);
            	echo '.';
            	//echo $filePath;
               /* if(empty($filter) || preg_match($filter, $path)) $results[] = $path;
                $this->generateInterceptor($base_dir, $path);
                */

            } elseif($value != "." && $value != "..") {

                $this->DirContents($path, $base_dir.'/'.$value,$filter, $results);
            }
        }
        return $results;
    }
}