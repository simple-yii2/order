<?php

namespace cms\purchase\common\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order';
    }

}
