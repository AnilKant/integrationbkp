<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
	    	'css/bootstrap.min.css',
	    	'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
	    	'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
	    	'css/AdminLTE.min.css',	
	    	'css/_all-skins.min.css',	
	    	'css/blue.css',
	    	'css/morris.css',
	    	'css/jquery-jvectormap-1.2.2.css',
	    	'css/datepicker3.css',
	    	'css/daterangepicker-bs3.css',	
	    	'css/bootstrap3-wysihtml5.min.css',	
	        'css/site.css',
    ];
    public $js = [
    		'js/bootstrap.min.js',
    		'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
    		'js/morris.min.js',
    		'js/jquery.sparkline.min.js',
    		'js/jquery-jvectormap-1.2.2.min.js',
    		'js/jquery-jvectormap-world-mill-en.js',
    		'js/jquery.knob.js',
    		'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js',
    		'js/daterangepicker.js',
    		'js/bootstrap-datepicker.js',
    		'js/bootstrap3-wysihtml5.all.min.js',
    		'js/jquery.slimscroll.min.js',
    		'js/fastclick.min.js',
    		'js/app.min.js',
    		'js/dashboard.js',
    		'js/demo.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
