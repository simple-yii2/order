<?php

namespace cms\purchase\frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use cms\purchase\common\helpers\DeliveryHelper;
use cms\purchase\common\models\Order;
use cms\purchase\common\models\OrderDelivery;

class OrderDeliveryForm extends Model
{

    /**
     * @var integer
     */
    public $store_id;

    /**
     * @var string
     */
    public $serviceName;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $street;

    /**
     * @var string
     */
    public $house;

    /**
     * @var string
     */
    public $apartment;

    /**
     * @var string
     */
    public $entrance;

    /**
     * @var string
     */
    public $entryphone;

    /**
     * @var integer
     */
    public $floor;

    /**
     * @var string
     */
    public $recipient;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    private $_method;

    /**
     * @var OrderDelivery
     */
    private $_object;

    /**
     * @inheritdoc
     */
    public function __construct(OrderDelivery $object = null, $config = [])
    {
        //delivery methods
        $methods = DeliveryHelper::getDeliveryMethods();

        //object
        if ($object === null) {
            $method = ArrayHelper::getValue(array_values($methods), 0);
            $object = $method === null ? new OrderDelivery : clone $method;
        }
        $this->_object = $object;
        
        //method
        $methodKeys = array_flip(array_map(function ($v) {return get_class($v);}, DeliveryHelper::getDeliveryMethods()));
        $this->method = ArrayHelper::getValue($methodKeys, get_class($object));

        parent::__construct(array_replace([
            'store_id' => $object->store_id,
            'serviceName' => $object->serviceName,
            'city' => $object->city,
            'street' => $object->street,
            'house' => $object->house,
            'apartment' => $object->apartment,
            'entrance' => $object->entrance,
            'entryphone' => $object->entryphone,
            'floor' => $object->floor,
            'recipient' => $object->recipient,
            'phone' => $object->phone,
            'comment' => $object->comment,
        ], $config));
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'method' => Yii::t('purchase', 'Delivery method'),
            'store_id' => Yii::t('purchase', 'Pickup point'),
            'serviceName' => Yii::t('purchase', 'Delivery service name'),
            'city' => Yii::t('purchase', 'City'),
            'street' => Yii::t('purchase', 'Street'),
            'house' => Yii::t('purchase', 'House'),
            'apartment' => Yii::t('purchase', 'Apartment'),
            'entrance' => Yii::t('purchase', 'Entrance'),
            'entryphone' => Yii::t('purchase', 'Entryphone'),
            'floor' => Yii::t('purchase', 'Floor'),
            'recipient' => Yii::t('purchase', 'Recipient name'),
            'phone' => Yii::t('purchase', 'Phone'),
            'comment' => Yii::t('purchase', 'Comment'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            ['method', 'required'],
            [['store_id', 'floor'], 'integer'],
            [['serviceName', 'city', 'street', 'entrance', 'recipient'], 'string', 'max' => 100],
            [['house', 'apartment', 'entryphone'], 'string', 'max' => 10],
            ['phone', 'string', 'max' => 20],
            ['comment', 'string', 'max' => 200],
        ];

        $required = [];
        foreach (DeliveryHelper::getDeliveryMethods() as $deliveryKey => $delivery) {
            foreach ($delivery->rules() as $rule) {
                $rule['on'] = 'delivery_' . $deliveryKey;
                $rules[] = $rule;

                if ($rule[1] == 'required') {
                    $required = array_merge($required, (array) $rule[0]);
                }
            }
        }
        $rules[] = [$required, 'required', 'on' => self::SCENARIO_DEFAULT];

        return $rules;
    }

    /**
     * Object getter
     * @return OrderDelivery
     */
    public function getObject()
    {
        return $this->_object;
    }

    /**
     * Method getter
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Method setter
     * @param string $value 
     * @return void
     */
    public function setMethod($value)
    {
        $this->_method = $value;

        $method = ArrayHelper::getValue(DeliveryHelper::getDeliveryMethods(), $value);
        if ($method === null) {
            return;
        }

        if (get_class($this->_object) == get_class($method)) {
            return;
        }

        //get attributes
        $preventAttributeNames = ['class', 'name', '_fields'];
        $attributes = array_diff_key($this->_object->getAttributes(), array_flip($preventAttributeNames));
        $this->_object->street = 'test';
        $dirtyAttributeNames = array_merge($preventAttributeNames, array_keys($this->_object->getDirtyAttributes()));

        //object
        $this->_object = clone $method;
        $object = $this->_object;
        $object->setAttributes($attributes, false);
        if ($attributes['id']) {
            $this->_object->setIsNewRecord(false);
        }

        //set attributes
        foreach ($dirtyAttributeNames as $name) {
            $object->markAttributeDirty($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate() !== true) {
            return false;
        }

        $this->scenario = 'delivery_' . $this->method;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        $this->scenario = self::SCENARIO_DEFAULT;
    }

    /**
     * Calculate delivery amount
     * @return float
     */
    public function calc(Order $order)
    {
        $this->applyObject();

        $object = $this->_object;
        $object->populateRelation('order', $order);
        $object->calcAmount();

        return $object->amount;
    }

    /**
     * Save delivery
     * @param Order $order 
     * @param boolean $runValidation 
     * @return boolean
     */
    public function save(Order $order, $runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        $this->applyObject();

        $object = $this->_object;
        $object->populateRelation('order', $order);
        $object->calcAmount();
        $object->calcDays();

        $order->link('delivery', $object);

        return true;
    }

    /**
     * Apply object with form data
     * @return void
     */
    private function applyObject()
    {
        $this->_object->setAttributes([
            'store_id' => (integer) $this->store_id,
            'serviceName' => $this->serviceName,
            'city' => $this->city,
            'street' => $this->street,
            'house' => $this->house,
            'apartment' => $this->apartment,
            'entrance' => $this->entrance,
            'entryphone' => $this->entryphone,
            'floor' => $this->floor === '' ? null : (integer) $this->floor,
            'recipient' => $this->recipient,
            'phone' => $this->phone,
            'comment' => $this->comment,
        ], false);
    }

}
