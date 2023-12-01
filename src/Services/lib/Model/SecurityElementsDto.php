<?php

namespace App\Services\lib\Model;

use App\Services\lib\ObjectSerializer;

class SecurityElementsDto implements ModelInterface, \ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'SecurityElementsDto';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'date_time' => 'string',
'qr_code' => 'string',
'code_me_ce_fdgi' => 'string',
'counters' => 'string',
'nim' => 'string',
'error_code' => 'string',
'error_desc' => 'string'    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'date_time' => null,
'qr_code' => null,
'code_me_ce_fdgi' => null,
'counters' => null,
'nim' => null,
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
        'date_time' => 'dateTime',
'qr_code' => 'qrCode',
'code_me_ce_fdgi' => 'codeMECeFDGI',
'counters' => 'counters',
'nim' => 'nim',
'error_code' => 'errorCode',
'error_desc' => 'errorDesc'    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'date_time' => 'setDateTime',
'qr_code' => 'setQrCode',
'code_me_ce_fdgi' => 'setCodeMeCeFdgi',
'counters' => 'setCounters',
'nim' => 'setNim',
'error_code' => 'setErrorCode',
'error_desc' => 'setErrorDesc'    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'date_time' => 'getDateTime',
'qr_code' => 'getQrCode',
'code_me_ce_fdgi' => 'getCodeMeCeFdgi',
'counters' => 'getCounters',
'nim' => 'getNim',
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
        $this->container['date_time'] = isset($data['date_time']) ? $data['date_time'] : null;
        $this->container['qr_code'] = isset($data['qr_code']) ? $data['qr_code'] : null;
        $this->container['code_me_ce_fdgi'] = isset($data['code_me_ce_fdgi']) ? $data['code_me_ce_fdgi'] : null;
        $this->container['counters'] = isset($data['counters']) ? $data['counters'] : null;
        $this->container['nim'] = isset($data['nim']) ? $data['nim'] : null;
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
     * Gets date_time
     *
     * @return string
     */
    public function getDateTime()
    {
        return $this->container['date_time'];
    }

    /**
     * Sets date_time
     *
     * @param string $date_time date_time
     *
     * @return $this
     */
    public function setDateTime($date_time)
    {
        $this->container['date_time'] = $date_time;

        return $this;
    }

    /**
     * Gets qr_code
     *
     * @return string
     */
    public function getQrCode()
    {
        return $this->container['qr_code'];
    }

    /**
     * Sets qr_code
     *
     * @param string $qr_code qr_code
     *
     * @return $this
     */
    public function setQrCode($qr_code)
    {
        $this->container['qr_code'] = $qr_code;

        return $this;
    }

    /**
     * Gets code_me_ce_fdgi
     *
     * @return string
     */
    public function getCodeMeCeFdgi()
    {
        return $this->container['code_me_ce_fdgi'];
    }

    /**
     * Sets code_me_ce_fdgi
     *
     * @param string $code_me_ce_fdgi code_me_ce_fdgi
     *
     * @return $this
     */
    public function setCodeMeCeFdgi($code_me_ce_fdgi)
    {
        $this->container['code_me_ce_fdgi'] = $code_me_ce_fdgi;

        return $this;
    }

    /**
     * Gets counters
     *
     * @return string
     */
    public function getCounters()
    {
        return $this->container['counters'];
    }

    /**
     * Sets counters
     *
     * @param string $counters counters
     *
     * @return $this
     */
    public function setCounters($counters)
    {
        $this->container['counters'] = $counters;

        return $this;
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
