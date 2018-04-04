<?php

namespace cms\purchase\frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use cms\catalog\frontend\helpers\PriceHelper;
use cms\purchase\common\helpers\DeliveryHelper;
use cms\purchase\frontend\helpers\CartHelper;
use cms\purchase\frontend\forms\OrderForm;

class OrderController extends Controller
{

    /**
     * Order checkout
     * @return string
     */
    public function actionCheckout()
    {
        $cart = CartHelper::getCart();
        if ($cart === null) {
            return $this->goHome();
        }

        //check delivery methods
        $methods = DeliveryHelper::getDeliveryMethods();
        if (empty($methods)) {
            return $this->goHome();
        }

        //order form
        $form = new OrderForm($cart);
        if ($form->load(Yii::$app->getRequest()->post()) && $form->save()) {
            return $this->redirect(['payment']);
        }

        return $this->render('checkout', ['form' => $form]);
    }

    /**
     * Choose order payment method
     * @return string
     */
    public function actionPayment()
    {
        return $this->render('payment', ['form' => $form]);
    }

    /**
     * Delivery calculation
     * @return string
     */
    public function actionCalcDelivery()
    {
        $cart = CartHelper::getCart();
        if ($cart === null) {
            throw new BadRequestHttpException('Object not found.');
        }

        $form = new OrderForm($cart);
        $form->load(Yii::$app->getRequest()->post());

        return Json::encode(PriceHelper::render('span', $form->calcDelivery(), $cart->currency));
    }

}
