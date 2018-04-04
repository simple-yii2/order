<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use cms\catalog\common\models\Store;
use cms\purchase\common\helpers\DeliveryHelper;

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
$method = $form->getObject();

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

?>
<div id="orderdelivery">
    <fieldset>
        <legend><?= Html::encode(Yii::t('purchase', 'Delivery method')) ?></legend>
        <?= $activeForm->field($form, 'method')->radioList($methodItems, ['data-fields' => $methodsFields, 'data-url-calc' => Url::toRoute(['calc-delivery'])]) ?>
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
</div>
