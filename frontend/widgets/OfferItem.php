<?php

namespace cms\order\frontend\widgets;

use Yii;
use yii\helpers\Html;
use cms\order\frontend\widgets\assets\OfferItemAsset;

class OfferItem extends \cms\catalog\frontend\widgets\OfferItem
{

	public function init()
	{
		parent::init();

		OfferItemAsset::register($this->view);

		$this->buttons[] = function ($model) {
			$title = Yii::t('catalog', 'Buy');
			return Html::a('<span class="glyphicon glyphicon-shopping-cart"></span> ' . $title, ['/order/cart/add', 'id' => $model->id], ['class' => 'btn btn-primary offer-cart']);
		};
	}

}
