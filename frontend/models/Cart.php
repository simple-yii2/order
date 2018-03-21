<?php

namespace cms\purchase\frontend\models;

use cms\catalog\common\models\Product;
use cms\catalog\frontend\helpers\CurrencyHelper;
use cms\purchase\common\models\Order;
use cms\purchase\common\models\OrderProduct;

class Cart extends Order
{

	/**
	 * @inheritdoc
	 */
	public function __construct($config = [])
	{
		$config['createDate'] = gmdate('Y-m-d H:i:s');
		$config['state'] = self::STATE_CART;
		parent::__construct($config);
	}

	/**
	 * Find cart for current user
	 * @param integer $user_id 
	 * @return static
	 */
	public static function findByUser($user_id)
	{
		static::find()->andWhere(['user_id' => $user_id])->one();
	}

	/**
	 * Calculate mount and total
	 * @return void
	 */
	public function calc()
	{
		$count = $amount = $discountAmount = $totalAmount = 0;
		foreach ($this->products as $item) {
			$count += $item->count;
			$amount += $item->amount;
			$discountAmount += $item->discountAmount;
			$totalAmount += $item->totalAmount;
		}

		$this->count = $count;
		$this->amount = $amount;
		$this->discountAmount = $discountAmount;
		$this->totalAmount = $totalAmount;

		// $row = $this->getProducts()->select([
		// 	'SUM([[count]]) as [[count]]',
		// 	'SUM([[amount]]) as [[amount]]',
		// 	'SUM([[discountAmount]]) as [[discountAmount]]',
		// 	'SUM([[totalAmount]]) as [[totalAmount]]',
		// ])->asArray()->one();
		// $this->setAttributes($row, false);
	}

	/**
	 * Add product to cart
	 * @param Product $product 
	 * @param integer $count 
	 * @return OrderProduct
	 */
	public function addProduct(Product $product, $count = 1)
	{
		$orderProduct = $this->getProducts()->andWhere(['product_id' => $product->id])->one();

		if ($orderProduct === null) {
			$orderProduct = new OrderProduct([
				'product_id' => $product->id,
				'productName' => $product->name,
				'productModel' => $product->model,
				'count' => $count,
			]);
		} else {
			$orderProduct->count += $count;
		}
		$orderProduct->calc($this->currency);

		$transaction = $this->db->beginTransaction();
		try {
			$this->link('products', $orderProduct);

			$this->modifyDate = gmdate('Y-m-d H:i:s');
			$this->calc();
			$this->save();

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}

		$orderProduct->populateRelation('product', $product);

		return $orderProduct;
	}

	/**
	 * Remove product from cart
	 * @param Product $product 
	 * @return void
	 */
	public function removeProduct(Product $product)
	{
		$orderProduct = $this->getProducts()->andWhere(['product_id' => $product->id])->one();

		if ($orderProduct === null) {
			return;
		}

		$transaction = $this->db->beginTransaction();
		try {
			$this->unlink('products', $orderProduct, true);

			$this->modifyDate = gmdate('Y-m-d H:i:s');
			$this->calc();
			$this->save();

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}

	/**
	 * Update product count in cart
	 * @param Product $product 
	 * @param integer $count 
	 * @return void
	 */
	public function updateProductCount(Product $product, $count)
	{
		$orderProduct = $this->getProducts()->andWhere(['product_id' => $product->id])->one();

		if ($orderProduct === null) {
			return;
		}

		if ($count < 1) {
			$count = 1;
		}
		if ($count > 999) {
			$count = 999;
		}
		if ($product->quantity && $count > $product->quantity) {
			$count = $product->quantity;
		}

		$orderProduct->count = $count;
		$orderProduct->calc($this->currency);

		$transaction = $this->db->beginTransaction();
		try {
			$orderProduct->save(false);

			$this->modifyDate = gmdate('Y-m-d H:i:s');
			$this->calc();
			$this->save();

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}

}
