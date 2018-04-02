<?php

namespace cms\purchase\backend;

use Yii;
use cms\components\BackendModule;

class Module extends BackendModule
{

    /**
     * @var array delivery methods available. Every item is the class name or config.
     */
    public $delivery;

    /**
     * @inheritdoc
     */
    public static function moduleName()
    {
        return 'purchase';
    }

    /**
     * @inheritdoc
     */
    public function cmsMenu($base)
    {
        if (!Yii::$app->getUser()->can('Purchase')) {
            return [];
        }

        $items = [];
        $items[] = ['label' => Yii::t('purchase', 'Delivery methods'), 'url' => ["$base/purchase/delivery/index"]];

        return [
            ['label' => Yii::t('purchase', 'Purchases'), 'items' => $items],
        ];
    }

}
