<?php

namespace cms\purchase\frontend\assets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\AssetBundle;

class CartApiAsset extends AssetBundle
{

	public $js = [
		'cart-api.js',
	];

	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		$this->sourcePath = __DIR__ . '/cart-api';
		parent::init();
	}

	public function registerAssetFiles($view)
	{
		parent::registerAssetFiles($view);

		//make urls
		$urls = array_map(function ($v) {
			return Url::toRoute(["/purchase/cart/{$v}"]);
		}, ['add', 'remove', 'count']);

		//register urls as global js var
		$view->registerJs('var cartUrls=' . Json::htmlEncode($urls), $view::POS_HEAD);
	}

}
