<?php

namespace cms\purchase\frontend\widgets;

use Yii;
use yii\helpers\Html;
use cms\purchase\frontend\Module;
use cms\purchase\frontend\helpers\CartHelper;
use cms\purchase\frontend\widgets\assets\ProductItemAsset;

class ProductItem extends \cms\catalog\frontend\widgets\ProductItem
{

    public $cartClassDefault = 'btn btn-primary';

    public $cartClassActive = 'btn btn-default';

    public $cartLabelDefault;

    public $cartLabelActive;

    public function init()
    {
        parent::init();

        Module::cmsTranslation();

        ProductItemAsset::register($this->view);

        if ($this->cartLabelDefault === null) {
            $this->cartLabelDefault = Yii::t('purchase', 'Buy');
        }
        if ($this->cartLabelActive === null) {
            $this->cartLabelActive = Yii::t('purchase', 'In the cart');
        }

        $this->addCartButton();
    }

    protected function AddCartButton()
    {
        $widget = $this;

        //add to cart
        $this->buttons[] = function ($model) use ($widget) {
            $options = ['class' => 'product-cart-add', 'data-id' => $model->id];
            Html::addCssClass($options, $widget->cartClassDefault);
            if (CartHelper::isProductInCart($model)) {
                Html::addCssClass($options, 'hidden');
            }
            return Html::a('<span class="glyphicon glyphicon-shopping-cart"></span> ' . $widget->cartLabelDefault, ['/purchase/cart/add', 'id' => $model->id], $options);
        };

        //in the cart
        $this->buttons[] = function ($model) use ($widget) {
            $options = ['class' => 'product-cart', 'data-id' => $model->id];
            Html::addCssClass($options, $widget->cartClassActive);
            if (!CartHelper::isProductInCart($model)) {
                Html::addCssClass($options, 'hidden');
            }
            return Html::a('<span class="glyphicon glyphicon-shopping-cart"></span> ' . $widget->cartLabelActive, ['/purchase/cart/index'], $options);
        };

    }

}
