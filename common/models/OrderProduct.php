<?php

namespace cms\purchase\common\models;

use yii\db\ActiveRecord;
use cms\catalog\common\models\Product;
use cms\catalog\frontend\helpers\CurrencyHelper;

class OrderProduct extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'purchase_order_product';
	}

	/**
	 * Product relation
	 * @return yii\db\ActiveQueryInterface
	 */
	public function getProduct()
	{
		return $this->hasOne(Product::className(), ['id' => 'product_id']);
	}

	/**
	 * Calculate amount and total
	 * @param cms\catalog\common\models\Currency $currency 
	 * @return void
	 */
	public function calc($currency)
	{
		$this->price = CurrencyHelper::calc($this->product->price, $this->product->currency, $currency);

		$this->amount = $this->price * $this->count;
		$this->totalAmount = $this->amount - $this->discountAmount;
	}

}
