<?php

namespace cms\purchase\frontend\widgets\assets;

use yii\web\AssetBundle;

class OfferItemAsset extends AssetBundle
{

	public $css = [
		'offer-item.css',
	];

	public $js = [
		'offer-item.js',
	];

	public function init()
	{
		$this->sourcePath = __DIR__ . '/offer-item';
		parent::init();
	}

}
