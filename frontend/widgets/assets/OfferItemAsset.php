<?php

namespace cms\order\frontend\widgets\assets;

use yii\web\AssetBundle;

class OfferItemAsset extends AssetBundle
{

	public $css = [
		'offer-item.css',
	];

	public function init()
	{
		$this->sourcePath = __DIR__ . '/offer-item';
		parent::init();
	}

}
