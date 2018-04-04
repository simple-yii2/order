<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use cms\catalog\frontend\helpers\PriceHelper;
use cms\purchase\frontend\assets\OrderFormAsset;
use cms\purchase\frontend\helpers\CartHelper;

OrderFormAsset::register($this);

$title = Yii::t('purchase', 'Order checkout');
$this->title = $title;

//cart
$cart = CartHelper::getCart();

?>
<h1><?= Html::encode($title) ?></h1>

<?php $activeForm = ActiveForm::begin([
    'id' => 'order-delivery-form',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'fieldConfig' => function ($model, $attribute) {
        $config = ['enableLabel' => false, 'inputOptions' => ['class' => 'form-control'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-12']];
        $label = $model->getAttributeLabel($attribute);
        if ($model->isAttributeRequired($attribute)) {
            $label .= '*';
        }
        $config['inputOptions']['placeholder'] = $label;
        return $config;
    },
]) ?>

    <?= $this->render('checkout/contact', ['activeForm' => $activeForm, 'form' => $form]) ?>
    
    <?= $this->render('checkout/delivery', ['activeForm' => $activeForm, 'form' => $form->delivery]) ?>

    <div class="row">
        <div class="order-total col-sm-8">
            <div class="order-total-info-wrapper">
                <div class="order-total-info">
                    <strong><?= Html::encode(Yii::t('purchase', 'Your order')) ?>:</strong>
                    <div><span><?= Html::encode(Yii::t('purchase', 'Contents')) ?>:</span> <?= Html::encode(Yii::t('purchase', '{n,plural,=1{# product} other{# products}}', ['n' => $cart->count])) ?>, <span class="order-subtotal-amount"><?= PriceHelper::render('span', $cart->subtotalAmount, $cart->currency) ?></span></div>
                    <div><span><?= Html::encode(Yii::t('purchase', 'Delivery')) ?>:</span> <span class="order-total-delivery-name"><?= Html::encode($form->delivery->getObject()->name) ?></span>, <span class="order-delivery-amount"><?= PriceHelper::render('span', $cart->deliveryAmount, $cart->currency) ?></span></div>
                </div>
                <div class="order-total-amount-block">
                    <span><?= Html::encode(Yii::t('purchase', 'Total')) ?>:</span>
                    <div class="order-total-amount"><?= PriceHelper::render('span', $cart->totalAmount, $cart->currency) ?></div>
                </div>
            </div>
            <div class="order-total-buttons">
                <?= Html::a(Yii::t('purchase', 'Back to cart'), ['cart/index'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton(Yii::t('purchase', 'Next'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>
