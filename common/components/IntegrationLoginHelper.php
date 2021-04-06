<?php 
namespace common\components;

use Yii;
use frontend\modules\merchant\Registry;

class IntegrationLoginHelper extends ManagerLoginHelper
{
	const DELIMETER = '/';

	public function getIntegrationModules()
	{
		$DS = DIRECTORY_SEPARATOR;

		$key = 'integration_modules';
		if(Registry::exist($key))
		{
			return Registry::get($key);
		}
		else
		{
			$configData = require Yii::getAlias('@frontend') . "{$DS}config{$DS}main.php";
	        $modules = $configData['modules'];

	        Registry::set($key, $modules);

	        return $modules;
		}
	}

	public function getMarketplaceIntegrationModules()
	{
		$DS = DIRECTORY_SEPARATOR;

		$key = 'marketplace_integration_modules';
		if(Registry::exist($key))
		{
			return Registry::get($key);
		}
		else
		{
			$modules = [];

			if(file_exists(Yii::getAlias('@rootdir')."{$DS}..{$DS}marketplace-integration{$DS}frontend{$DS}config{$DS}main.php")) {
	        	$configData = require Yii::getAlias('@rootdir')."{$DS}..{$DS}marketplace-integration{$DS}frontend{$DS}config{$DS}main.php";
	        } elseif(file_exists(Yii::getAlias('@rootdir')."{$DS}..{$DS}shopify-integration{$DS}frontend{$DS}config{$DS}main.php")) {
				$configData = require Yii::getAlias('@rootdir')."{$DS}..{$DS}shopify-integration{$DS}frontend{$DS}config{$DS}main.php";
			}

			if(isset($configData)) {
				$modules = $configData['modules'];
			}

			Registry::set($key, $modules);

	        return $modules;
		}
	}

	public function getModuleData($module)
	{
		$key = $module.'_data';
		if(Registry::exist($key))
		{
			return Registry::get($key);
		}
		else
		{
			$moduleData = [];

			// $integration_apps = $this->getIntegrationModules();
	  		// $marketplace_integration_apps = $this->getMarketplaceIntegrationModules();

			// if(array_key_exists($module, $integration_apps))
			// {
			// 	$moduleData = $integration_apps[$module];
			// }
			// elseif(array_key_exists($module, $marketplace_integration_apps))
			// {
			// 	$moduleData = $marketplace_integration_apps[$module];
			// }

			$modules = $this->getIntegrationModules();

			if(strpos($module, self::DELIMETER) !== false)
	        {
	        	$explode = explode(self::DELIMETER, $module);
		    	$module = $explode[0];
		    	$submodule = $explode[1];

		    	if(isset($modules[$module]))
		    	{
		    		if(is_object($modules[$module])) {
						$moduleobj = $modules[$module];
					} else {
						$moduleobj = $modules[$module]['class'];
					}

					foreach ($this->getSubmoduleList($moduleobj) as $sub_module => $sub_module_data) {
			            if($sub_module == $submodule) {
			            	$moduleData = $sub_module_data;
			            	break;
			            }
			        }
		    	}
	        }
	        else
	        {
	        	if(array_key_exists($module, $modules)) {
					$moduleData = $modules[$module];
				}
	        }

			if($moduleData) {
				Registry::set($key, $moduleData);
			}

			return $moduleData;
		}
	}

	public function getSubmoduleList($module_class)
	{
		if(method_exists($module_class,'getSubModuleDetails')) {
	        return $module_class::getSubModuleDetails();
	    }
	    else {
	    	return [];
	    }
	}

	public function getModuleList()
	{
		$key = 'modules_list';
		if(Registry::exist($key))
		{
			return Registry::get($key);
		}
		else
		{
			// $module_list = [];

			// $integration_apps = $this->getIntegrationModules();
	  		// $marketplace_integration_apps = $this->getMarketplaceIntegrationModules();

			// if(array_key_exists($module, $integration_apps))
			// {
			// 	$module_list = $integration_apps;
			// }
			// elseif(array_key_exists($module, $marketplace_integration_apps))
			// {
			// 	$module_list = $marketplace_integration_apps;
			// }

			$module_list = $this->getIntegrationModules();
			if($module_list) {
				Registry::set($key, $module_list);
			}

			return $module_list;
		}
	}
}