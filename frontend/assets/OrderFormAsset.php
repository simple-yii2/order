<?php

namespace cms\purchase\frontend\assets;

use yii\web\AssetBundle;

class OrderFormAsset extends AssetBundle
{

    public $css = [
        'order-form.css',
    ];

    public $js = [
        'order-form.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/order-form';
        parent::init();
    }

}
