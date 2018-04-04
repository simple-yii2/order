<?php

namespace cms\purchase\frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use cms\purchase\common\models\Order;

/**
 * Order checkout form
 */
class OrderForm extends Model
{

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var OrderDeliveryForm
     */
    public $delivery;

    /**
     * @var Order
     */
    private $_object;

    /**
     * @inheritdoc
     */
    public function __construct(Order $object = null, $config = [])
    {
        if ($object === null) {
            $object = new Order;
        }

        $this->_object = $object;

        $deliveryConfig = ArrayHelper::getValue($config, 'delivery', []);
        unset($config['delivery']);

        parent::__construct(array_replace([
            'phone' => $object->phone,
            'email' => $object->email,
            'name' => $object->name,
        ], $config));

        $this->delivery = new OrderDeliveryForm($object->delivery, $deliveryConfig);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('purchase', 'Phone'),
            'email' => Yii::t('purchase', 'E-mail'),
            'name' => Yii::t('purchase', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['phone', 'string', 'max' => 20],
            [['email', 'name'], 'string', 'max' => 100],
            ['email', 'email'],
            [['phone', 'name'], 'required'],
            ['delivery', function () {
                if (!$this->delivery->validate()) {
                    $this->addError('delivery', $this->delivery->getErrors());
                }
            }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $b = parent::load($data, $formName);

        if ($this->delivery->load($data, $formName)) {
            $b = true;
        }

        return $b;
    }

    /**
     * Save order
     * @param boolean $runValidation 
     * @return boolean
     */
    public function save($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        $object = $this->_object;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->delivery->save($object, false);
            $object->setAttributes([
                'phone' => $this->phone,
                'name' => $this->name,
                'email' => $this->email,
                'deliveryAmount' => $this->delivery->getObject()->amount,
            ], false);
            $object->calc();
            $object->save(false);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return true;
    }

    /**
     * Calculate delivery amount
     * @return float
     */
    public function calcDelivery()
    {
        return $this->delivery->calc($this->_object);
    }

}
