<?php

namespace cms\purchase\common\delivery;

use Yii;
use cms\catalog\common\models\Currency;
use cms\purchase\common\models\OrderDelivery;

/**
 * Courier delivery method
 */
class Courier extends OrderDelivery
{

    /**
     * @inheritdoc
     */
    public static function deliveryName()
    {
        return Yii::t('purchase', 'Courier');
    }

    /**
     * @inheritdoc
     */
    public static function availableFields()
    {
        return [
            'street',
            'house',
            'apartment',
            'entrance',
            'entryphone',
            'floor',
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
            [['street', 'house', 'recipient', 'phone'], 'required'],
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
