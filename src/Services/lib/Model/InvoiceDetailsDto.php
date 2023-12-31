<?php

namespace App\Services\lib\Model;

use App\Services\lib\ObjectSerializer;

class InvoiceDetailsDto implements ModelInterface, \ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'InvoiceDetailsDto';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'ifu' => 'string',
'aib' => 'App\Services\lib\Model\AibGroupTypeEnum',
'type' => 'App\Services\lib\Model\InvoiceTypeEnum',
'items' => 'App\Services\lib\Model\ItemDto[]',
'client' => 'App\Services\lib\Model\ClientDto',
'operator' => 'App\Services\lib\Model\OperatorDto',
'payment' => 'App\Services\lib\Model\PaymentDto[]',
'reference' => 'string',
'error_code' => 'string',
'error_desc' => 'string'    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'ifu' => null,
'aib' => null,
'type' => null,
'items' => null,
'client' => null,
'operator' => null,
'payment' => null,
'reference' => null,
'error_code' => null,
'error_desc' => null    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'ifu' => 'ifu',
'aib' => 'aib',
'type' => 'type',
'items' => 'items',
'client' => 'client',
'operator' => 'operator',
'payment' => 'payment',
'reference' => 'reference',
'error_code' => 'errorCode',
'error_desc' => 'errorDesc'    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'ifu' => 'setIfu',
'aib' => 'setAib',
'type' => 'setType',
'items' => 'setItems',
'client' => 'setClient',
'operator' => 'setOperator',
'payment' => 'setPayment',
'reference' => 'setReference',
'error_code' => 'setErrorCode',
'error_desc' => 'setErrorDesc'    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'ifu' => 'getIfu',
'aib' => 'getAib',
'type' => 'getType',
'items' => 'getItems',
'client' => 'getClient',
'operator' => 'getOperator',
'payment' => 'getPayment',
'reference' => 'getReference',
'error_code' => 'getErrorCode',
'error_desc' => 'getErrorDesc'    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['ifu'] = isset($data['ifu']) ? $data['ifu'] : null;
        $this->container['aib'] = isset($data['aib']) ? $data['aib'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['items'] = isset($data['items']) ? $data['items'] : null;
        $this->container['client'] = isset($data['client']) ? $data['client'] : null;
        $this->container['operator'] = isset($data['operator']) ? $data['operator'] : null;
        $this->container['payment'] = isset($data['payment']) ? $data['payment'] : null;
        $this->container['reference'] = isset($data['reference']) ? $data['reference'] : null;
        $this->container['error_code'] = isset($data['error_code']) ? $data['error_code'] : null;
        $this->container['error_desc'] = isset($data['error_desc']) ? $data['error_desc'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets ifu
     *
     * @return string
     */
    public function getIfu()
    {
        return $this->container['ifu'];
    }

    /**
     * Sets ifu
     *
     * @param string $ifu ifu
     *
     * @return $this
     */
    public function setIfu($ifu)
    {
        $this->container['ifu'] = $ifu;

        return $this;
    }

    /**
     * Gets aib
     *
     * @return App\Services\lib\Model\AibGroupTypeEnum
     */
    public function getAib()
    {
        return $this->container['aib'];
    }

    /**
     * Sets aib
     *
     * @param App\Services\lib\Model\AibGroupTypeEnum $aib aib
     *
     * @return $this
     */
    public function setAib($aib)
    {
        $this->container['aib'] = $aib;

        return $this;
    }

    /**
     * Gets type
     *
     * @return App\Services\lib\Model\InvoiceTypeEnum
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param App\Services\lib\Model\InvoiceTypeEnum $type type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets items
     *
     * @return App\Services\lib\Model\ItemDto[]
     */
    public function getItems()
    {
        return $this->container['items'];
    }

    /**
     * Sets items
     *
     * @param App\Services\lib\Model\ItemDto[] $items items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->container['items'] = $items;

        return $this;
    }

    /**
     * Gets client
     *
     * @return App\Services\lib\Model\ClientDto
     */
    public function getClient()
    {
        return $this->container['client'];
    }

    /**
     * Sets client
     *
     * @param App\Services\lib\Model\ClientDto $client client
     *
     * @return $this
     */
    public function setClient($client)
    {
        $this->container['client'] = $client;

        return $this;
    }

    /**
     * Gets operator
     *
     * @return App\Services\lib\Model\OperatorDto
     */
    public function getOperator()
    {
        return $this->container['operator'];
    }

    /**
     * Sets operator
     *
     * @param App\Services\lib\Model\OperatorDto $operator operator
     *
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->container['operator'] = $operator;

        return $this;
    }

    /**
     * Gets payment
     *
     * @return App\Services\lib\Model\PaymentDto[]
     */
    public function getPayment()
    {
        return $this->container['payment'];
    }

    /**
     * Sets payment
     *
     * @param App\Services\lib\Model\PaymentDto[] $payment payment
     *
     * @return $this
     */
    public function setPayment($payment)
    {
        $this->container['payment'] = $payment;

        return $this;
    }

    /**
     * Gets reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->container['reference'];
    }

    /**
     * Sets reference
     *
     * @param string $reference reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->container['reference'] = $reference;

        return $this;
    }

    /**
     * Gets error_code
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->container['error_code'];
    }

    /**
     * Sets error_code
     *
     * @param string $error_code error_code
     *
     * @return $this
     */
    public function setErrorCode($error_code)
    {
        $this->container['error_code'] = $error_code;

        return $this;
    }

    /**
     * Gets error_desc
     *
     * @return string
     */
    public function getErrorDesc()
    {
        return $this->container['error_desc'];
    }

    /**
     * Sets error_desc
     *
     * @param string $error_desc error_desc
     *
     * @return $this
     */
    public function setErrorDesc($error_desc)
    {
        $this->container['error_desc'] = $error_desc;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
