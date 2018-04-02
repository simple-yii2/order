<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use cms\catalog\common\models\Store;
use cms\catalog\frontend\helpers\PriceHelper;
use cms\purchase\common\helpers\DeliveryHelper;
use cms\purchase\frontend\assets\DeliveryFormAsset;
use cms\purchase\frontend\helpers\CartHelper;

DeliveryFormAsset::register($this);

$title = Yii::t('purchase', 'Order checkout');
$this->title = $title;

//delivery methods
$methods = DeliveryHelper::getDeliveryMethods();
$methodItems = array_map(function ($v) {
    return $v->name;
}, $methods);
$methodsFields = array_map(function ($v) use ($form) {
    return array_map(function ($v) use ($form) {
        return Html::getInputId($form, $v);
    }, $v::availableFields());
}, $methods);
$method = ArrayHelper::getValue($methods, $form->method);

//stores
$stores = [];
foreach (Store::find()->andWhere(['type' => [Store::TYPE_PICKUP, Store::TYPE_SALES_PICKUP]])->all() as $item) {
    $stores[$item->id] = $item->name;
}

//fields options
$fields = ArrayHelper::getValue($method, 'fields', []);
$options = [];
foreach ($form->getAttributes() as $name => $value) {
    $options[$name] = ['options' => ['class' => 'form-group']];
    if (!in_array($name, $fields)) {
        Html::addCssClass($options[$name]['options'], 'hidden');
    }
}

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

    <fieldset>
        <legend><?= Html::encode(Yii::t('purchase', 'Delivery method')) ?></legend>
        <?= $activeForm->field($form, 'method')->radioList($methodItems, ['data-fields' => $methodsFields, 'data-url-calc' => Url::toRoute(['delivery-calc'])]) ?>
    </fieldset>

    <fieldset>
        <div class="row">
            <div class="col-sm-8"><?= $activeForm->field($form, 'store_id', $options['store_id'])->dropDownList($stores) ?></div>
        </div>
    </fieldset>

    <fieldset>
        <div class="row">
            <div class="col-sm-8"><?= $activeForm->field($form, 'serviceName', $options['serviceName']) ?></div>
        </div>
    </fieldset>

    <fieldset>
        <div class="row">
            <div class="col-sm-8"><?= $activeForm->field($form, 'city', $options['city']) ?></div>
        </div>
        <div class="row">
            <div class="col-sm-4"><?= $activeForm->field($form, 'street', $options['street']) ?></div>
            <div class="col-sm-2"><?= $activeForm->field($form, 'house', $options['house']) ?></div>
            <div class="col-sm-2"><?= $activeForm->field($form, 'apartment', $options['apartment']) ?></div>
        </div>
        <div class="row">
            <div class="col-sm-2"><?= $activeForm->field($form, 'entrance', $options['entrance']) ?></div>
            <div class="col-sm-2"><?= $activeForm->field($form, 'entryphone', $options['entryphone']) ?></div>
            <div class="col-sm-2"><?= $activeForm->field($form, 'floor', $options['floor']) ?></div>
        </div>
    </fieldset>

    <fieldset>
        <div class="row">
            <div class="col-sm-4"><?= $activeForm->field($form, 'recipient', $options['recipient']) ?></div>
            <div class="col-sm-4"><?= $activeForm->field($form, 'phone', $options['phone']) ?></div>
        </div>
    </fieldset>

    <fieldset>
        <div class="row">
            <div class="col-sm-8"><?= $activeForm->field($form, 'comment', $options['comment'])->textarea(['rows' => 5]) ?></div>
        </div>
    </fieldset>

    <div class="row">
        <div class="order-total col-sm-8">
            <div class="order-total-info-wrapper">
                <div class="order-total-info">
                    <strong><?= Html::encode(Yii::t('purchase', 'Your order')) ?>:</strong>
                    <div><span><?= Html::encode(Yii::t('purchase', 'Contents')) ?>:</span> <?= Html::encode(Yii::t('purchase', '{n,plural,=1{# product} other{# products}}', ['n' => $cart->count])) ?>, <span class="order-subtotal-amount"><?= PriceHelper::render('span', $cart->subtotalAmount, $cart->currency) ?></span></div>
                    <div><span><?= Html::encode(Yii::t('purchase', 'Delivery')) ?>:</span> <span class="order-total-delivery-name"><?= Html::encode($method->name) ?></span>, <span class="order-delivery-amount"><?= PriceHelper::render('span', $cart->deliveryAmount, $cart->currency) ?></span></div>
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
