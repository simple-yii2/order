<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;
use cms\catalog\frontend\helpers\PriceHelper;
use cms\purchase\frontend\assets\CartFormAsset;

CartFormAsset::register($this);

$title = Yii::t('purchase', 'Shopping cart');
$this->title = $title;

//items
if ($cart === null) {
    $items = [];
} else {
    $items = $cart->getProducts()->with(['product'])->all();
}

//cart currency
$currency = $cart === null ? null : $cart->currency;

//total
$total = '';
if ($cart !== null) {
    $label = Html::tag('span', Html::encode(Yii::t('purchase', 'Total') . ':'));
    $amount = Html::tag('div', PriceHelper::render('span', $cart->subtotalAmount, $currency), ['class' => 'cart-list-total-amount']);
    $block = Html::tag('div', $label . $amount, ['class' => 'cart-list-total-amount-block']);

    $order = Html::a(Yii::t('purchase', 'Proceed to checkout'), ['order/checkout'], ['class' => 'btn btn-primary']);
    $buttons = Html::tag('div', $order, ['class' => 'cart-list-total-buttons']);
    $total = Html::tag('div', $block . $buttons, ['class' => 'cart-list-total']);
}

?>
<h1><?= Html::encode($title) ?></h1>

<?= ListView::widget([
    'id' => 'cart-list',
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $items,
        'key' => 'product_id',
        'pagination' => false,
    ]),
    'emptyText' => Yii::t('purchase', 'Your shopping cart is empty'),
    'layout' => '{items}',
    'itemOptions' => ['class' => 'cart-item'],
    'itemView' => function ($model) use ($currency) {
        $product = $model->product;

        $s = Html::encode(trim($product->name . ' ' . $product->model));
        $title = Html::tag('div', Html::a($s, ['/catalog/product/view', 'alias' => $product->alias]), ['class' => 'cart-item-title']);

        $s = Html::a('<span class="glyphicon glyphicon-trash"></span>', ['remove', 'id' => $product->id], ['class' => 'btn btn-default product-cart-remove']);
        $buttons = Html::tag('div', $s, ['class' => 'cart-item-buttons']);

        $s = Html::img($product->thumb);
        $thumb = Html::tag('div', $s, ['class' => 'cart-item-thumb']);

        $s = PriceHelper::render('span', $model->totalAmount, $currency);
        $totalAmount = Html::tag('div', $s, ['class' => 'cart-item-total-amount']);

        $o = ['class' => 'btn btn-default product-cart-count-dec'];
        if ($model->count <= 1) $o['disabled'] = true;
        $dec = Html::tag('span', Html::button('<span class="glyphicon glyphicon-minus"></span>', $o), ['class' => 'input-group-btn']);

        $o = ['class' => 'btn btn-default product-cart-count-inc'];
        if ($product->quantity && $model->count >= $product->quantity) $o['disabled'] = true;
        $inc = Html::tag('span', Html::button('<span class="glyphicon glyphicon-plus"></span>', $o), ['class' => 'input-group-btn']);

        $input = Html::textInput('count', $model->count, ['class' => 'form-control', 'maxLength' => 3, 'data-value' => $model->count, 'data-quantity' => $product->quantity]);
        $count = Html::tag('div', $dec . $input . $inc, ['class' => 'cart-item-count input-group']);

        return $buttons. $title . $thumb . $count . $totalAmount;
    },
]) ?>

<?= $total ?>
