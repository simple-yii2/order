<?php

namespace cms\purchase\frontend\assets;

use yii\web\AssetBundle;

class DeliveryFormAsset extends AssetBundle
{

    public $css = [
        'delivery-form.css',
    ];

    public $js = [
        'delivery-form.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/delivery-form';
        parent::init();
    }

}
