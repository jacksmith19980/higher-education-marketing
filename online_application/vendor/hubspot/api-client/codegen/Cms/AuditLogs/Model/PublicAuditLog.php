<?php
/**
 * PublicAuditLog
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  HubSpot\Client\Cms\AuditLogs
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * CMS Audit Logs
 *
 * Use this endpoint to query audit logs of CMS changes that occurred on your HubSpot account.
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

namespace HubSpot\Client\Cms\AuditLogs\Model;

use \ArrayAccess;
use \HubSpot\Client\Cms\AuditLogs\ObjectSerializer;

/**
 * PublicAuditLog Class Doc Comment
 *
 * @category Class
 * @package  HubSpot\Client\Cms\AuditLogs
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null
 */
class PublicAuditLog implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'PublicAuditLog';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'object_id' => 'string',
        'user_id' => 'string',
        'timestamp' => '\DateTime',
        'object_name' => 'string',
        'full_name' => 'string',
        'event' => 'string',
        'object_type' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'object_id' => null,
        'user_id' => null,
        'timestamp' => 'date-time',
        'object_name' => null,
        'full_name' => null,
        'event' => null,
        'object_type' => null
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
        'object_id' => 'objectId',
        'user_id' => 'userId',
        'timestamp' => 'timestamp',
        'object_name' => 'objectName',
        'full_name' => 'fullName',
        'event' => 'event',
        'object_type' => 'objectType'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'object_id' => 'setObjectId',
        'user_id' => 'setUserId',
        'timestamp' => 'setTimestamp',
        'object_name' => 'setObjectName',
        'full_name' => 'setFullName',
        'event' => 'setEvent',
        'object_type' => 'setObjectType'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'object_id' => 'getObjectId',
        'user_id' => 'getUserId',
        'timestamp' => 'getTimestamp',
        'object_name' => 'getObjectName',
        'full_name' => 'getFullName',
        'event' => 'getEvent',
        'object_type' => 'getObjectType'
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

    const EVENT_CREATED = 'CREATED';
    const EVENT_UPDATED = 'UPDATED';
    const EVENT_PUBLISHED = 'PUBLISHED';
    const EVENT_DELETED = 'DELETED';
    const EVENT_UNPUBLISHED = 'UNPUBLISHED';
    const OBJECT_TYPE_BLOG = 'BLOG';
    const OBJECT_TYPE_BLOG_POST = 'BLOG_POST';
    const OBJECT_TYPE_LANDING_PAGE = 'LANDING_PAGE';
    const OBJECT_TYPE_WEBSITE_PAGE = 'WEBSITE_PAGE';
    const OBJECT_TYPE_TEMPLATE = 'TEMPLATE';
    const OBJECT_TYPE_MODULE = 'MODULE';
    const OBJECT_TYPE_GLOBAL_MODULE = 'GLOBAL_MODULE';
    const OBJECT_TYPE_SERVERLESS_FUNCTION = 'SERVERLESS_FUNCTION';
    const OBJECT_TYPE_DOMAIN = 'DOMAIN';
    const OBJECT_TYPE_URL_MAPPING = 'URL_MAPPING';
    const OBJECT_TYPE_EMAIL = 'EMAIL';
    const OBJECT_TYPE_CONTENT_SETTINGS = 'CONTENT_SETTINGS';
    const OBJECT_TYPE_HUBDB_TABLE = 'HUBDB_TABLE';
    const OBJECT_TYPE_KNOWLEDGE_BASE_ARTICLE = 'KNOWLEDGE_BASE_ARTICLE';
    const OBJECT_TYPE_KNOWLEDGE_BASE = 'KNOWLEDGE_BASE';
    const OBJECT_TYPE_THEME = 'THEME';
    const OBJECT_TYPE_CSS = 'CSS';
    const OBJECT_TYPE_JS = 'JS';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getEventAllowableValues()
    {
        return [
            self::EVENT_CREATED,
            self::EVENT_UPDATED,
            self::EVENT_PUBLISHED,
            self::EVENT_DELETED,
            self::EVENT_UNPUBLISHED,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getObjectTypeAllowableValues()
    {
        return [
            self::OBJECT_TYPE_BLOG,
            self::OBJECT_TYPE_BLOG_POST,
            self::OBJECT_TYPE_LANDING_PAGE,
            self::OBJECT_TYPE_WEBSITE_PAGE,
            self::OBJECT_TYPE_TEMPLATE,
            self::OBJECT_TYPE_MODULE,
            self::OBJECT_TYPE_GLOBAL_MODULE,
            self::OBJECT_TYPE_SERVERLESS_FUNCTION,
            self::OBJECT_TYPE_DOMAIN,
            self::OBJECT_TYPE_URL_MAPPING,
            self::OBJECT_TYPE_EMAIL,
            self::OBJECT_TYPE_CONTENT_SETTINGS,
            self::OBJECT_TYPE_HUBDB_TABLE,
            self::OBJECT_TYPE_KNOWLEDGE_BASE_ARTICLE,
            self::OBJECT_TYPE_KNOWLEDGE_BASE,
            self::OBJECT_TYPE_THEME,
            self::OBJECT_TYPE_CSS,
            self::OBJECT_TYPE_JS,
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
        $this->container['object_id'] = $data['object_id'] ?? null;
        $this->container['user_id'] = $data['user_id'] ?? null;
        $this->container['timestamp'] = $data['timestamp'] ?? null;
        $this->container['object_name'] = $data['object_name'] ?? null;
        $this->container['full_name'] = $data['full_name'] ?? null;
        $this->container['event'] = $data['event'] ?? null;
        $this->container['object_type'] = $data['object_type'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['object_id'] === null) {
            $invalidProperties[] = "'object_id' can't be null";
        }
        if ($this->container['user_id'] === null) {
            $invalidProperties[] = "'user_id' can't be null";
        }
        if ($this->container['timestamp'] === null) {
            $invalidProperties[] = "'timestamp' can't be null";
        }
        if ($this->container['object_name'] === null) {
            $invalidProperties[] = "'object_name' can't be null";
        }
        if ($this->container['full_name'] === null) {
            $invalidProperties[] = "'full_name' can't be null";
        }
        if ($this->container['event'] === null) {
            $invalidProperties[] = "'event' can't be null";
        }
        $allowedValues = $this->getEventAllowableValues();
        if (!is_null($this->container['event']) && !in_array($this->container['event'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'event', must be one of '%s'",
                $this->container['event'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['object_type'] === null) {
            $invalidProperties[] = "'object_type' can't be null";
        }
        $allowedValues = $this->getObjectTypeAllowableValues();
        if (!is_null($this->container['object_type']) && !in_array($this->container['object_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'object_type', must be one of '%s'",
                $this->container['object_type'],
                implode("', '", $allowedValues)
            );
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
     * Gets object_id
     *
     * @return string
     */
    public function getObjectId()
    {
        return $this->container['object_id'];
    }

    /**
     * Sets object_id
     *
     * @param string $object_id The ID of the object.
     *
     * @return self
     */
    public function setObjectId($object_id)
    {
        $this->container['object_id'] = $object_id;

        return $this;
    }

    /**
     * Gets user_id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->container['user_id'];
    }

    /**
     * Sets user_id
     *
     * @param string $user_id The ID of the user who caused the event.
     *
     * @return self
     */
    public function setUserId($user_id)
    {
        $this->container['user_id'] = $user_id;

        return $this;
    }

    /**
     * Gets timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->container['timestamp'];
    }

    /**
     * Sets timestamp
     *
     * @param \DateTime $timestamp The timestamp at which the event occurred.
     *
     * @return self
     */
    public function setTimestamp($timestamp)
    {
        $this->container['timestamp'] = $timestamp;

        return $this;
    }

    /**
     * Gets object_name
     *
     * @return string
     */
    public function getObjectName()
    {
        return $this->container['object_name'];
    }

    /**
     * Sets object_name
     *
     * @param string $object_name The internal name of the object in HubSpot.
     *
     * @return self
     */
    public function setObjectName($object_name)
    {
        $this->container['object_name'] = $object_name;

        return $this;
    }

    /**
     * Gets full_name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->container['full_name'];
    }

    /**
     * Sets full_name
     *
     * @param string $full_name The name of the user who caused the event.
     *
     * @return self
     */
    public function setFullName($full_name)
    {
        $this->container['full_name'] = $full_name;

        return $this;
    }

    /**
     * Gets event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->container['event'];
    }

    /**
     * Sets event
     *
     * @param string $event The type of event that took place (CREATED, UPDATED, PUBLISHED, DELETED, UNPUBLISHED).
     *
     * @return self
     */
    public function setEvent($event)
    {
        $allowedValues = $this->getEventAllowableValues();
        if (!in_array($event, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'event', must be one of '%s'",
                    $event,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['event'] = $event;

        return $this;
    }

    /**
     * Gets object_type
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->container['object_type'];
    }

    /**
     * Sets object_type
     *
     * @param string $object_type The type of the object (BLOG, LANDING_PAGE, DOMAIN, HUBDB_TABLE etc.)
     *
     * @return self
     */
    public function setObjectType($object_type)
    {
        $allowedValues = $this->getObjectTypeAllowableValues();
        if (!in_array($object_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'object_type', must be one of '%s'",
                    $object_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['object_type'] = $object_type;

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


