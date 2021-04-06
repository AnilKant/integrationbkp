<?php 
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use frontend\modules\merchant\Registry;

abstract class ManagerLoginHelper extends component
{
	const MANAGER_LOGIN_KEY = 'manager_login_key';

	const MODULE_PARAM = 'm';

	//in hours
	const CACHE_DURATION = 6;

	const MERCHANT_AUTH_USER = 'cedcoss';

	const MERCHANT_AUTH_PASSWORD = 'cedcoss007';

	public function getLoginasMerchantUrl($module, $controller='merchant')
	{
		$baseUrl = trim(trim(Yii::getAlias('@webbaseurl'), '/'), 'integration');

		$integration_apps = array_keys($this->getIntegrationModules());

        $marketplace_integration_apps = array_keys($this->getMarketplaceIntegrationModules());

		$loginUrl = '';

		if(strpos($module, $this::DELIMETER) !== false)
        {
        	$explode = explode($this::DELIMETER, $module);
	    	$_module = $explode[0];
	    	$_submodule = $explode[1];

	    	if(in_array($_module, $integration_apps))
			{
				$loginUrl = $baseUrl.'integration/';
			}
			elseif(in_array($_module, $marketplace_integration_apps))
			{
				$loginUrl = $baseUrl.'marketplace-integration/';
			}
        }
        else
        {
        	if(in_array($module, $integration_apps))
			{
				$loginUrl = $baseUrl.'integration/';
			}
			elseif(in_array($module, $marketplace_integration_apps))
			{
				$loginUrl = $baseUrl.'marketplace-integration/';
			}
        }

		if($loginUrl)
		{
			$action = $this->getLoginasActionName($module);

			$midParam = $this->getMerchantIdParamName($module);

			$module_id = urlencode($module);
			
			if(property_exists(Yii::$app->user, 'identityClass') && Yii::$app->user->identity->username == 'automated_script') {
			    $loginUrl = $loginUrl."$controller/$action?m={$module_id}&loginbyrahulsbot=yes&{$midParam}=";
    	    }
    	    else {
    	        //$loginUrl = $loginUrl."$module/$controller/$action?$midParam=";
			    $loginUrl = $loginUrl."$controller/$action?m={$module_id}&{$midParam}=";   
    	    }

			return $loginUrl;
		}
		else
		{
			return false;
		}
	}

	public function getCache()
	{
		return Yii::createObject('common\components\Cache');
	}

	public function getLoginasActionName($module)
	{
		$key = self::MANAGER_LOGIN_KEY."_$module";

		$cache = $this->getCache();
        $name = $cache->get($key);

        if($name) {
        	return $name;
        }
        else {
        	$name = $this->generateRandomString();

        	$set = $cache->set($key, $name, (self::CACHE_DURATION*60*60));
        	if(!$set) {
        		throw new Exception("Unable to set cache");
        	}
        }

        return $name;
	}

	private function generateRandomString()
	{
		$length = 10;

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
	}

	public function getMerchantIdParamName($module)
	{
		return 'mid';
	}

	public function getLoginSuccessUrl($module)
	{
        $method = 'getLoginSuccessUrl';

		$redirectUrl = $module.'/site/index';

		$data = $this->getModuleData($module);
		if($data) {
			$class = $data['class'];
			if(method_exists($class, $method)) {
				$redirectUrl = $class::$method();
			}			
		}

		return $redirectUrl;
	}

	public function beforeLogin($module)
	{
		$method = 'beforeLogin';

		foreach ($this->getModuleList() as $module => $data)
		{
			if($data && $data['class'])
			{
				$class = $data['class'];
				if(!empty($sub_modules = $this->getSubmoduleList($class))) {
					foreach ($sub_modules as $subModuleId => $subModuleData) {
						if(isset($subModuleData['class']) && method_exists($subModuleData['class'], $method)) {
							$subModuleData['class']::$method();
						}
					}
				}
				else {
					if(method_exists($class, $method)) {
						$class::$method();
					}
				}
			}
		}
	}

	public function afterLogin($module)
	{
		$method = 'afterLogin';

		foreach ($this->getModuleList() as $module => $data)
		{
			if($data && $data['class'])
			{
				$class = $data['class'];
				if(!empty($sub_modules = $this->getSubmoduleList($class))) {
					foreach ($sub_modules as $subModuleId => $subModuleData) {
						if(isset($subModuleData['class']) && method_exists($subModuleData['class'], $method)) {
							$subModuleData['class']::$method();
						}
					}
				}
				else {
					if(method_exists($class, $method)) {
						$class::$method();
					}
				}
			}
		}
	}
}