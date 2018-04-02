<?php

namespace cms\purchase\frontend\controllers;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use cms\catalog\common\models\Product;
use cms\catalog\frontend\helpers\PriceHelper;
use cms\purchase\frontend\helpers\CartHelper;
use cms\purchase\frontend\models\Cart;

class CartController extends Controller
{

	/**
	 * Cart page - product list with count and reove functionality
	 * @return string
	 */
	public function actionIndex()
	{
		$cart = CartHelper::getCart();

		return $this->render('index', [
			'cart' => $cart,
		]);
	}

	/**
	 * Add product to cart
	 * @param integer $id 
	 * @return string
	 */
	public function actionAdd($id)
	{
		$product = Product::findOne($id);
		if ($product === null) {
			throw new BadRequestHttpException('Object not found.');
		}

		$cart = CartHelper::getCart(true);

		$cart->addProduct($product);

		return Json::encode($this->getCartInfo($cart, $product));
	}

	/**
	 * Delete product from cart
	 * @param integer $id 
	 * @return string
	 */
	public function actionRemove($id)
	{
		$product = Product::findOne($id);
		if ($product === null) {
			throw new BadRequestHttpException('Object not found.');
		}

		$cart = CartHelper::getCart(true);

		$cart->removeProduct($product);

		return Json::encode($this->getCartInfo($cart, $product));
	}

	/**
	 * Update product count in cart
	 * @param integer $id 
	 * @param integer $count 
	 * @return string
	 */
	public function actionCount($id, $count)
	{
		$product = Product::findOne($id);
		if ($product === null) {
			throw new BadRequestHttpException('Object not found.');
		}

		$cart = CartHelper::getCart(true);

		$cart->updateProductCount($product, $count);

		return Json::encode($this->getCartInfo($cart, $product));
	}

	/**
	 * Make cart info for client-side
	 * @param Cart $cart 
	 * @param Product $product 
	 * @return string
	 */
	private function getCartInfo(Cart $cart, Product $product)
	{
		$currency = $cart->currency;

		$items = [];
		foreach ($cart->getProducts()->with(['product'])->all() as $object) {
			$item = [
				'id' => $object->product_id,
				'name' => $object->productName,
				'model' => $object->productModel,
				'count' => $object->count,
				'price' => PriceHelper::render('span', $object->price, $currency),
				'discountAmount' => PriceHelper::render('span', $object->discountAmount),
				'totalAmount' => PriceHelper::render('span', $object->totalAmount),
				'thumb' => ArrayHelper::getValue($object->product, 'thumb'),
				'quantity' => $object->product->quantity,
			];
			$items[] = $item;
		}

		return [
			'count' => $cart->count,
			'amount' => PriceHelper::render('span', $cart->amount),
			'discountAmount' => PriceHelper::render('span', $cart->discountAmount),
			'subtotalAmount' => PriceHelper::render('span', $cart->subtotalAmount),
			'totalAmount' => PriceHelper::render('span', $cart->totalAmount),
			'product_id' => $product->id,
			'items' => $items,
		];
	}

}
