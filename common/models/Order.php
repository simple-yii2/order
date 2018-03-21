<?php

namespace cms\purchase\common\models;

use yii\db\ActiveRecord;
use cms\catalog\common\models\Currency;

class Order extends ActiveRecord
{

	//states
	const STATE_CART = 1;
	const STATE_NEW = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order';
    }

    /**
     * Currency relation
     * @return yii\db\ActiveQueryInterface
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * Order products relation
     * @return yii\db\ActiveQueryInterface
     */
    public function getProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }

}
