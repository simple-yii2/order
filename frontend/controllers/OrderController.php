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
// use cms\purchase\frontend\forms\DeliveryForm;

class OrderController extends Controller
{

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






        // $delivaryForm = new DeliveryForm($cart->delivery);
        // if ($form->method === null) {
        //     $form->method = array_keys($methods)[0];
        // }

        if ($form->load(Yii::$app->getRequest()->post()) && $form->save()) {
            return $this->redirect(['payment']);
        }

        return $this->render('checkout', ['form' => $form]);
    }

    public function actionDeliveryCalc()
    {
        $cart = CartHelper::getCart();
        if ($cart === null) {
            throw new BadRequestHttpException('Object not found.');
        }

        $form = new DeliveryForm($cart->delivery);
        $form->load(Yii::$app->getRequest()->post());

        return Json::encode(PriceHelper::render('span', $form->calc(), $cart->currency));
    }

}
