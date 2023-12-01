<?php

namespace App\Services\lib\Model;

use App\Services\lib\ObjectSerializer;

class TaxGroupsDto implements ModelInterface, \ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'TaxGroupsDto';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'a' => 'int',
'b' => 'int',
'c' => 'int',
'd' => 'int',
'e' => 'int',
'f' => 'int',
'aib_a' => 'int',
'aib_b' => 'int'    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'a' => 'int32',
'b' => 'int32',
'c' => 'int32',
'd' => 'int32',
'e' => 'int32',
'f' => 'int32',
'aib_a' => 'int32',
'aib_b' => 'int32'    ];

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
        'a' => 'a',
'b' => 'b',
'c' => 'c',
'd' => 'd',
'e' => 'e',
'f' => 'f',
'aib_a' => 'aibA',
'aib_b' => 'aibB'    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'a' => 'setA',
'b' => 'setB',
'c' => 'setC',
'd' => 'setD',
'e' => 'setE',
'f' => 'setF',
'aib_a' => 'setAibA',
'aib_b' => 'setAibB'    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'a' => 'getA',
'b' => 'getB',
'c' => 'getC',
'd' => 'getD',
'e' => 'getE',
'f' => 'getF',
'aib_a' => 'getAibA',
'aib_b' => 'getAibB'    ];

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
        $this->container['a'] = isset($data['a']) ? $data['a'] : null;
        $this->container['b'] = isset($data['b']) ? $data['b'] : null;
        $this->container['c'] = isset($data['c']) ? $data['c'] : null;
        $this->container['d'] = isset($data['d']) ? $data['d'] : null;
        $this->container['e'] = isset($data['e']) ? $data['e'] : null;
        $this->container['f'] = isset($data['f']) ? $data['f'] : null;
        $this->container['aib_a'] = isset($data['aib_a']) ? $data['aib_a'] : null;
        $this->container['aib_b'] = isset($data['aib_b']) ? $data['aib_b'] : null;
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
     * Gets a
     *
     * @return int
     */
    public function getA()
    {
        return $this->container['a'];
    }

    /**
     * Sets a
     *
     * @param int $a a
     *
     * @return $this
     */
    public function setA($a)
    {
        $this->container['a'] = $a;

        return $this;
    }

    /**
     * Gets b
     *
     * @return int
     */
    public function getB()
    {
        return $this->container['b'];
    }

    /**
     * Sets b
     *
     * @param int $b b
     *
     * @return $this
     */
    public function setB($b)
    {
        $this->container['b'] = $b;

        return $this;
    }

    /**
     * Gets c
     *
     * @return int
     */
    public function getC()
    {
        return $this->container['c'];
    }

    /**
     * Sets c
     *
     * @param int $c c
     *
     * @return $this
     */
    public function setC($c)
    {
        $this->container['c'] = $c;

        return $this;
    }

    /**
     * Gets d
     *
     * @return int
     */
    public function getD()
    {
        return $this->container['d'];
    }

    /**
     * Sets d
     *
     * @param int $d d
     *
     * @return $this
     */
    public function setD($d)
    {
        $this->container['d'] = $d;

        return $this;
    }

    /**
     * Gets e
     *
     * @return int
     */
    public function getE()
    {
        return $this->container['e'];
    }

    /**
     * Sets e
     *
     * @param int $e e
     *
     * @return $this
     */
    public function setE($e)
    {
        $this->container['e'] = $e;

        return $this;
    }

    /**
     * Gets f
     *
     * @return int
     */
    public function getF()
    {
        return $this->container['f'];
    }

    /**
     * Sets f
     *
     * @param int $f f
     *
     * @return $this
     */
    public function setF($f)
    {
        $this->container['f'] = $f;

        return $this;
    }

    /**
     * Gets aib_a
     *
     * @return int
     */
    public function getAibA()
    {
        return $this->container['aib_a'];
    }

    /**
     * Sets aib_a
     *
     * @param int $aib_a aib_a
     *
     * @return $this
     */
    public function setAibA($aib_a)
    {
        $this->container['aib_a'] = $aib_a;

        return $this;
    }

    /**
     * Gets aib_b
     *
     * @return int
     */
    public function getAibB()
    {
        return $this->container['aib_b'];
    }

    /**
     * Sets aib_b
     *
     * @param int $aib_b aib_b
     *
     * @return $this
     */
    public function setAibB($aib_b)
    {
        $this->container['aib_b'] = $aib_b;

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
