<?php

namespace cms\purchase\common\helpers;

use Yii;
use yii\base\Module;
use yii\helpers\ArrayHelper;

class DeliveryHelper
{

    /**
     * @var array
     */
    private static $_deliveryMethods;

    /**
     * Return available delivery method class names using application config ([[cms\purchase\backend\Module::$delivery]])
     * @return array
     */
    public static function getDeliveryMethods()
    {
        if (self::$_deliveryMethods !== null) {
            return self::$_deliveryMethods;
        }

        $classes = [];

        foreach (Yii::$app->getModules() as $module) {
            if ($module instanceof Module) {
                $class = Module::className();
            } elseif (is_array($module)) {
                $class = ArrayHelper::getValue($module, 'class', '');
            } else {
                $class = (string) $module;
            }

            if ($class == 'cms\Module') {
                $classes = ArrayHelper::getValue($module, ['modulesConfig', 'purchase', 'delivery'], [
                    'pickup' => 'cms\purchase\common\delivery\Pickup',
                    'courier' => 'cms\purchase\common\delivery\Courier',
                    'service' => 'cms\purchase\common\delivery\Service',
                ]);
                break;
            }
        }

        $deliveryMethods = [];
        foreach ($classes as $key => $class) {
            $deliveryMethods[$key] = new $class;
        }

        return self::$_deliveryMethods = $deliveryMethods;
    }

}
