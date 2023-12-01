<?php

namespace App\Services\lib\Model;

class EmcfInfoDto implements ModelInterface, \ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'EmcfInfoDto';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'nim' => 'string',
'status' => 'string',
'shop_name' => 'string',
'address1' => 'string',
'address2' => 'string',
'address3' => 'string',
'contact1' => 'string',
'contact2' => 'string',
'contact3' => 'string'    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'nim' => null,
'status' => null,
'shop_name' => null,
'address1' => null,
'address2' => null,
'address3' => null,
'contact1' => null,
'contact2' => null,
'contact3' => null    ];

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
        'nim' => 'nim',
'status' => 'status',
'shop_name' => 'shopName',
'address1' => 'address1',
'address2' => 'address2',
'address3' => 'address3',
'contact1' => 'contact1',
'contact2' => 'contact2',
'contact3' => 'contact3'    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'nim' => 'setNim',
'status' => 'setStatus',
'shop_name' => 'setShopName',
'address1' => 'setAddress1',
'address2' => 'setAddress2',
'address3' => 'setAddress3',
'contact1' => 'setContact1',
'contact2' => 'setContact2',
'contact3' => 'setContact3'    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'nim' => 'getNim',
'status' => 'getStatus',
'shop_name' => 'getShopName',
'address1' => 'getAddress1',
'address2' => 'getAddress2',
'address3' => 'getAddress3',
'contact1' => 'getContact1',
'contact2' => 'getContact2',
'contact3' => 'getContact3'    ];

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
        $this->container['nim'] = isset($data['nim']) ? $data['nim'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['shop_name'] = isset($data['shop_name']) ? $data['shop_name'] : null;
        $this->container['address1'] = isset($data['address1']) ? $data['address1'] : null;
        $this->container['address2'] = isset($data['address2']) ? $data['address2'] : null;
        $this->container['address3'] = isset($data['address3']) ? $data['address3'] : null;
        $this->container['contact1'] = isset($data['contact1']) ? $data['contact1'] : null;
        $this->container['contact2'] = isset($data['contact2']) ? $data['contact2'] : null;
        $this->container['contact3'] = isset($data['contact3']) ? $data['contact3'] : null;
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
     * Gets nim
     *
     * @return string
     */
    public function getNim()
    {
        return $this->container['nim'];
    }

    /**
     * Sets nim
     *
     * @param string $nim nim
     *
     * @return $this
     */
    public function setNim($nim)
    {
        $this->container['nim'] = $nim;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string $status status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets shop_name
     *
     * @return string
     */
    public function getShopName()
    {
        return $this->container['shop_name'];
    }

    /**
     * Sets shop_name
     *
     * @param string $shop_name shop_name
     *
     * @return $this
     */
    public function setShopName($shop_name)
    {
        $this->container['shop_name'] = $shop_name;

        return $this;
    }

    /**
     * Gets address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->container['address1'];
    }

    /**
     * Sets address1
     *
     * @param string $address1 address1
     *
     * @return $this
     */
    public function setAddress1($address1)
    {
        $this->container['address1'] = $address1;

        return $this;
    }

    /**
     * Gets address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->container['address2'];
    }

    /**
     * Sets address2
     *
     * @param string $address2 address2
     *
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->container['address2'] = $address2;

        return $this;
    }

    /**
     * Gets address3
     *
     * @return string
     */
    public function getAddress3()
    {
        return $this->container['address3'];
    }

    /**
     * Sets address3
     *
     * @param string $address3 address3
     *
     * @return $this
     */
    public function setAddress3($address3)
    {
        $this->container['address3'] = $address3;

        return $this;
    }

    /**
     * Gets contact1
     *
     * @return string
     */
    public function getContact1()
    {
        return $this->container['contact1'];
    }

    /**
     * Sets contact1
     *
     * @param string $contact1 contact1
     *
     * @return $this
     */
    public function setContact1($contact1)
    {
        $this->container['contact1'] = $contact1;

        return $this;
    }

    /**
     * Gets contact2
     *
     * @return string
     */
    public function getContact2()
    {
        return $this->container['contact2'];
    }

    /**
     * Sets contact2
     *
     * @param string $contact2 contact2
     *
     * @return $this
     */
    public function setContact2($contact2)
    {
        $this->container['contact2'] = $contact2;

        return $this;
    }

    /**
     * Gets contact3
     *
     * @return string
     */
    public function getContact3()
    {
        return $this->container['contact3'];
    }

    /**
     * Sets contact3
     *
     * @param string $contact3 contact3
     *
     * @return $this
     */
    public function setContact3($contact3)
    {
        $this->container['contact3'] = $contact3;

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
