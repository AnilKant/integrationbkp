<?php 
namespace common\components;

use Yii;
use yii\caching\FileCache;

class Cache extends FileCache
{
	public $cachePath = '@rootdir/common/cache';
}