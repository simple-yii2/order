<?php

namespace cms\order\backend;

use Yii;
use cms\components\BackendModule;

class Module extends BackendModule
{

    /**
     * @inheritdoc
     */
    public static function moduleName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function cmsMenu($base)
    {
        if (!Yii::$app->getUser()->can('Order')) {
            return [];
        }

        $items = [];
        $items[] = ['label' => Yii::t('order', 'Orders'), 'url' => ["$base/order/order/index"]];

        return [
            ['label' => Yii::t('order', 'Orders'), 'items' => $items],
        ];
    }

}
