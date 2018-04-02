<?php

namespace cms\purchase\common\models;

use yii\db\ActiveRecord;
use cms\catalog\common\models\Currency;

class OrderDelivery extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config['class'] = static::className();
        $config['name'] = static::deliveryName();
        $config['fields'] = static::availableFields();

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order_delivery';
    }

    /**
     * Order relation
     * @return yii\db\ActiveQueryInterface
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id'])->inverseOf('delivery');
    }

    /**
     * Name of delivery method
     * @return string
     */
    public static function deliveryName()
    {
        return '';
    }

    /**
     * Available field for delivery method
     * @return array
     */
    public static function availableFields()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        $class = $row['class'];
        $instance = new $class;

        if (!($instance instanceof static)) {
            $instance = new static;
        }

        return $instance;
    }

    /**
     * Fields getter
     * @return array
     */
    public function getFields()
    {
        $fields = @unserialize($this->_fields);
        if ($fields === false) {
            $fields = [];
        }

        return $fields;
    }

    /**
     * Fields setter
     * @param array $value 
     * @return void
     */
    public function setFields($value)
    {
        if (!is_array($value)) {
            return;
        }

        $this->_fields = serialize($value);
    }

    /**
     * Calculate delivery amount
     * @param Currency|null $currency 
     * @return void
     */
    public function calcAmount(Currency $currency = null)
    {

    }

    /**
     * Calculate delivery days
     * @return void
     */
    public function calcDays()
    {

    }

}
