<?php

namespace cms\purchase\frontend\helpers;

use Yii;
use yii\web\Cookie;
use cms\catalog\common\models\Product;
use cms\catalog\frontend\helpers\CurrencyHelper;
use cms\purchase\frontend\models\Cart;

class CartHelper
{

    //name for cart in cookies
    const COOKIE_NAME = 'cart';

    /**
     * @var Cart
     */
    private static $_cart = false;

    /**
     * @var array
     */
    private static $_product_ids;

    /**
     * Return cart for current user
     * @param boolean $forceCreate 
     * @return Cart
     */
    public static function getCart($forceCreate = false)
    {
        if (self::$_cart !== false && !(self::$_cart === null && $forceCreate))
            return self::$_cart;

        $user_id = Yii::$app->getUser()->getId();
        $cart = null;

        if (!empty($user_id)) {
            $cart = Cart::findByUser($user_id);
        }

        if ($cart === null) {
            $cart = Cart::findOne(Yii::$app->getRequest()->getCookies()->get(self::COOKIE_NAME));
        }

        if ($cart === null && $forceCreate) {
            $cart = new Cart(['user_id' => $user_id, 'currency_id' => CurrencyHelper::getApplicationCurrencyId()]);
            $cart->save();
            Yii::$app->getResponse()->getCookies()->add(new Cookie(['name' => self::COOKIE_NAME, 'value' => $cart->id]));
        }

        return self::$_cart = $cart;
    }

    public static function isProductInCart(Product $product)
    {
        $cart = self::getCart();
        if ($cart === null) {
            return false;
        }

        //cart products
        if (self::$_product_ids === null) {
            $ids = [];
            foreach ($cart->getProducts()->select(['product_id'])->asArray()->all() as $row) {
                $ids[$row['product_id']] = $row;
            }
            self::$_product_ids = $ids;
        }

        return array_key_exists($product->id, self::$_product_ids);
    }

}
