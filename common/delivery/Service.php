<?php

namespace cms\purchase\common\delivery;

use Yii;
use cms\catalog\common\models\Currency;
use cms\purchase\common\models\OrderDelivery;

/**
 * Delivery service method
 */
class Service extends OrderDelivery
{

    /**
     * @inheritdoc
     */
    public static function deliveryName()
    {
        return Yii::t('purchase', 'Delivery service');
    }

    /**
     * @inheritdoc
     */
    public static function availableFields()
    {
        return [
            'serviceName',
            'city',
            'street',
            'house',
            'apartment',
            'recipient',
            'phone',
            'comment',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serviceName', 'city', 'street', 'house', 'recipient', 'phone'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function calcAmount(Currency $currency = null)
    {
        $this->amount = 0;
    }

    /**
     * @inheritdoc
     */
    public function calcDays()
    {
        $this->days = 1;
    }

}
