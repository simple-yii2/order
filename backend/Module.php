<?php

namespace cms\purchase\backend;

use Yii;
use cms\components\BackendModule;

class Module extends BackendModule
{

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
        $items[] = ['label' => Yii::t('purchase', 'Orders'), 'url' => ["$base/purchase/order/index"]];

        return [
            ['label' => Yii::t('purchase', 'Purchases'), 'items' => $items],
        ];
    }

}
