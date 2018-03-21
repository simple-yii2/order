<?php

namespace cms\purchase\frontend\widgets\assets;

use yii\web\AssetBundle;

class ProductItemAsset extends AssetBundle
{

	public $css = [
		'product-item.css',
	];

	public $js = [
		'product-item.js',
	];

	public $depends = [
		'yii\web\JqueryAsset',
		'cms\purchase\frontend\assets\CartApiAsset',
	];

	public function init()
	{
		$this->sourcePath = __DIR__ . '/product-item';
		parent::init();
	}

}
