<?php
/**
 * PublicImportResponse
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  HubSpot\Client\Crm\Imports
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * CRM Imports
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: v3
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.4.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace HubSpot\Client\Crm\Imports\Model;

use \ArrayAccess;
use \HubSpot\Client\Crm\Imports\ObjectSerializer;

/**
 * PublicImportResponse Class Doc Comment
 *
 * @category Class
 * @description A current summary of the import, whether complete or not.
 * @package  HubSpot\Client\Crm\Imports
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null
 */
class PublicImportResponse implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'PublicImportResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'state' => 'string',
        'import_request_json' => 'object',
        'created_at' => '\DateTime',
        'metadata' => '\HubSpot\Client\Crm\Imports\Model\PublicImportMetadata',
        'import_name' => 'string',
        'updated_at' => '\DateTime',
        'opt_out_import' => 'bool',
        'id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'state' => null,
        'import_request_json' => null,
        'created_at' => 'date-time',
        'metadata' => null,
        'import_name' => null,
        'updated_at' => 'date-time',
        'opt_out_import' => null,
        'id' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'state' => 'state',
        'import_request_json' => 'importRequestJson',
        'created_at' => 'createdAt',
        'metadata' => 'metadata',
        'import_name' => 'importName',
        'updated_at' => 'updatedAt',
        'opt_out_import' => 'optOutImport',
        'id' => 'id'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'state' => 'setState',
        'import_request_json' => 'setImportRequestJson',
        'created_at' => 'setCreatedAt',
        'metadata' => 'setMetadata',
        'import_name' => 'setImportName',
        'updated_at' => 'setUpdatedAt',
        'opt_out_import' => 'setOptOutImport',
        'id' => 'setId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'state' => 'getState',
        'import_request_json' => 'getImportRequestJson',
        'created_at' => 'getCreatedAt',
        'metadata' => 'getMetadata',
        'import_name' => 'getImportName',
        'updated_at' => 'getUpdatedAt',
        'opt_out_import' => 'getOptOutImport',
        'id' => 'getId'
    ];

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
        return self::$openAPIModelName;
    }

    const STATE_STARTED = 'STARTED';
    const STATE_PROCESSING = 'PROCESSING';
    const STATE_DONE = 'DONE';
    const STATE_FAILED = 'FAILED';
    const STATE_CANCELED = 'CANCELED';
    const STATE_DEFERRED = 'DEFERRED';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStateAllowableValues()
    {
        return [
            self::STATE_STARTED,
            self::STATE_PROCESSING,
            self::STATE_DONE,
            self::STATE_FAILED,
            self::STATE_CANCELED,
            self::STATE_DEFERRED,
        ];
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
        $this->container['state'] = $data['state'] ?? null;
        $this->container['import_request_json'] = $data['import_request_json'] ?? null;
        $this->container['created_at'] = $data['created_at'] ?? null;
        $this->container['metadata'] = $data['metadata'] ?? null;
        $this->container['import_name'] = $data['import_name'] ?? null;
        $this->container['updated_at'] = $data['updated_at'] ?? null;
        $this->container['opt_out_import'] = $data['opt_out_import'] ?? null;
        $this->container['id'] = $data['id'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['state'] === null) {
            $invalidProperties[] = "'state' can't be null";
        }
        $allowedValues = $this->getStateAllowableValues();
        if (!is_null($this->container['state']) && !in_array($this->container['state'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'state', must be one of '%s'",
                $this->container['state'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['created_at'] === null) {
            $invalidProperties[] = "'created_at' can't be null";
        }
        if ($this->container['metadata'] === null) {
            $invalidProperties[] = "'metadata' can't be null";
        }
        if ($this->container['updated_at'] === null) {
            $invalidProperties[] = "'updated_at' can't be null";
        }
        if ($this->container['opt_out_import'] === null) {
            $invalidProperties[] = "'opt_out_import' can't be null";
        }
        if ($this->container['id'] === null) {
            $invalidProperties[] = "'id' can't be null";
        }
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
     * Gets state
     *
     * @return string
     */
    public function getState()
    {
        return $this->container['state'];
    }

    /**
     * Sets state
     *
     * @param string $state The status of the import.
     *
     * @return self
     */
    public function setState($state)
    {
        $allowedValues = $this->getStateAllowableValues();
        if (!in_array($state, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'state', must be one of '%s'",
                    $state,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['state'] = $state;

        return $this;
    }

    /**
     * Gets import_request_json
     *
     * @return object|null
     */
    public function getImportRequestJson()
    {
        return $this->container['import_request_json'];
    }

    /**
     * Sets import_request_json
     *
     * @param object|null $import_request_json import_request_json
     *
     * @return self
     */
    public function setImportRequestJson($import_request_json)
    {
        $this->container['import_request_json'] = $import_request_json;

        return $this;
    }

    /**
     * Gets created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->container['created_at'];
    }

    /**
     * Sets created_at
     *
     * @param \DateTime $created_at created_at
     *
     * @return self
     */
    public function setCreatedAt($created_at)
    {
        $this->container['created_at'] = $created_at;

        return $this;
    }

    /**
     * Gets metadata
     *
     * @return \HubSpot\Client\Crm\Imports\Model\PublicImportMetadata
     */
    public function getMetadata()
    {
        return $this->container['metadata'];
    }

    /**
     * Sets metadata
     *
     * @param \HubSpot\Client\Crm\Imports\Model\PublicImportMetadata $metadata metadata
     *
     * @return self
     */
    public function setMetadata($metadata)
    {
        $this->container['metadata'] = $metadata;

        return $this;
    }

    /**
     * Gets import_name
     *
     * @return string|null
     */
    public function getImportName()
    {
        return $this->container['import_name'];
    }

    /**
     * Sets import_name
     *
     * @param string|null $import_name import_name
     *
     * @return self
     */
    public function setImportName($import_name)
    {
        $this->container['import_name'] = $import_name;

        return $this;
    }

    /**
     * Gets updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->container['updated_at'];
    }

    /**
     * Sets updated_at
     *
     * @param \DateTime $updated_at updated_at
     *
     * @return self
     */
    public function setUpdatedAt($updated_at)
    {
        $this->container['updated_at'] = $updated_at;

        return $this;
    }

    /**
     * Gets opt_out_import
     *
     * @return bool
     */
    public function getOptOutImport()
    {
        return $this->container['opt_out_import'];
    }

    /**
     * Sets opt_out_import
     *
     * @param bool $opt_out_import Whether or not the import is a list of people disqualified from receiving emails.
     *
     * @return self
     */
    public function setOptOutImport($opt_out_import)
    {
        $this->container['opt_out_import'] = $opt_out_import;

        return $this;
    }

    /**
     * Gets id
     *
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param string $id id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

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
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
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
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


