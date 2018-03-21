<?php

namespace cms\purchase\frontend\assets;

use yii\web\AssetBundle;

class CartAsset extends AssetBundle
{

    public $css = [
        'cart.css',
    ];

    public $js = [
        'cart.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'cms\purchase\frontend\assets\CartApiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/cart';
        parent::init();
    }

}
