<?php

namespace cms\order\common\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

}
