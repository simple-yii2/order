<?php

namespace cms\purchase\frontend\assets;

use yii\web\AssetBundle;

class CartFormAsset extends AssetBundle
{

    public $css = [
        'cart-form.css',
    ];

    public $js = [
        'cart-form.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'cms\purchase\frontend\assets\CartApiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/cart-form';
        parent::init();
    }

}
