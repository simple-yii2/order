<?php

namespace cms\order\frontend;

use cms\components\FrontendModule;

/**
 * Catalog frontend module
 */
class Module extends FrontendModule
{

	/**
	 * @inheritdoc
	 */
	public static function moduleName()
	{
		return 'order';
	}

}
