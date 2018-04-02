<?php

namespace cms\purchase\common\delivery;

use Yii;
use cms\catalog\common\models\Currency;
use cms\purchase\common\models\OrderDelivery;

/**
 * Pickup delivery method
 */
class Pickup extends OrderDelivery
{

    /**
     * @inheritdoc
     */
    public static function deliveryName()
    {
        return Yii::t('purchase', 'Pickup');
    }

    /**
     * @inheritdoc
     */
    public static function availableFields()
    {
        return [
            'store_id',
            'comment',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['store_id', 'required'],
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
        $this->days = 0;
    }

}
