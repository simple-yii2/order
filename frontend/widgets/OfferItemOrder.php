<?php

namespace cms\catalog\frontend\widgets;

use Yii;
use yii\helpers\Html;

class OfferItemOrder extends OfferItem
{

	public function init()
	{
		parent::init();

		$this->buttons[] = function ($model) {
			$title = Yii::t('catalog', 'Buy');
			return Html::a('<span class="glyphicon glyphicon-shopping-cart"></span> ' . $title, ['cart', 'id' => $model->id], ['class' => 'btn btn-primary offer-cart']);
		};
	}

}
