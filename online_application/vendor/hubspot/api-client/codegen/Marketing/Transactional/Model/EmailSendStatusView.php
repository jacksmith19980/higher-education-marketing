<?php
/**
 * EmailSendStatusView
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  HubSpot\Client\Marketing\Transactional
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Transactional Email
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

namespace HubSpot\Client\Marketing\Transactional\Model;

use \ArrayAccess;
use \HubSpot\Client\Marketing\Transactional\ObjectSerializer;

/**
 * EmailSendStatusView Class Doc Comment
 *
 * @category Class
 * @description Describes the status of an email send request.
 * @package  HubSpot\Client\Marketing\Transactional
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null
 */
class EmailSendStatusView implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'EmailSendStatusView';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'event_id' => '\HubSpot\Client\Marketing\Transactional\Model\EventIdView',
        'status_id' => 'string',
        'send_result' => 'string',
        'requested_at' => '\DateTime',
        'started_at' => '\DateTime',
        'completed_at' => '\DateTime',
        'status' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'event_id' => null,
        'status_id' => null,
        'send_result' => null,
        'requested_at' => 'date-time',
        'started_at' => 'date-time',
        'completed_at' => 'date-time',
        'status' => null
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
        'event_id' => 'eventId',
        'status_id' => 'statusId',
        'send_result' => 'sendResult',
        'requested_at' => 'requestedAt',
        'started_at' => 'startedAt',
        'completed_at' => 'completedAt',
        'status' => 'status'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'event_id' => 'setEventId',
        'status_id' => 'setStatusId',
        'send_result' => 'setSendResult',
        'requested_at' => 'setRequestedAt',
        'started_at' => 'setStartedAt',
        'completed_at' => 'setCompletedAt',
        'status' => 'setStatus'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'event_id' => 'getEventId',
        'status_id' => 'getStatusId',
        'send_result' => 'getSendResult',
        'requested_at' => 'getRequestedAt',
        'started_at' => 'getStartedAt',
        'completed_at' => 'getCompletedAt',
        'status' => 'getStatus'
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

    const SEND_RESULT_SENT = 'SENT';
    const SEND_RESULT_IDEMPOTENT_IGNORE = 'IDEMPOTENT_IGNORE';
    const SEND_RESULT_QUEUED = 'QUEUED';
    const SEND_RESULT_IDEMPOTENT_FAIL = 'IDEMPOTENT_FAIL';
    const SEND_RESULT_THROTTLED = 'THROTTLED';
    const SEND_RESULT_EMAIL_DISABLED = 'EMAIL_DISABLED';
    const SEND_RESULT_PORTAL_SUSPENDED = 'PORTAL_SUSPENDED';
    const SEND_RESULT_INVALID_TO_ADDRESS = 'INVALID_TO_ADDRESS';
    const SEND_RESULT_BLOCKED_DOMAIN = 'BLOCKED_DOMAIN';
    const SEND_RESULT_PREVIOUSLY_BOUNCED = 'PREVIOUSLY_BOUNCED';
    const SEND_RESULT_EMAIL_UNCONFIRMED = 'EMAIL_UNCONFIRMED';
    const SEND_RESULT_PREVIOUS_SPAM = 'PREVIOUS_SPAM';
    const SEND_RESULT_PREVIOUSLY_UNSUBSCRIBED_MESSAGE = 'PREVIOUSLY_UNSUBSCRIBED_MESSAGE';
    const SEND_RESULT_PREVIOUSLY_UNSUBSCRIBED_PORTAL = 'PREVIOUSLY_UNSUBSCRIBED_PORTAL';
    const SEND_RESULT_INVALID_FROM_ADDRESS = 'INVALID_FROM_ADDRESS';
    const SEND_RESULT_CAMPAIGN_CANCELLED = 'CAMPAIGN_CANCELLED';
    const SEND_RESULT_VALIDATION_FAILED = 'VALIDATION_FAILED';
    const SEND_RESULT_MTA_IGNORE = 'MTA_IGNORE';
    const SEND_RESULT_BLOCKED_ADDRESS = 'BLOCKED_ADDRESS';
    const SEND_RESULT_PORTAL_OVER_LIMIT = 'PORTAL_OVER_LIMIT';
    const SEND_RESULT_PORTAL_EXPIRED = 'PORTAL_EXPIRED';
    const SEND_RESULT_PORTAL_MISSING_MARKETING_SCOPE = 'PORTAL_MISSING_MARKETING_SCOPE';
    const SEND_RESULT_MISSING_TEMPLATE_PROPERTIES = 'MISSING_TEMPLATE_PROPERTIES';
    const SEND_RESULT_MISSING_REQUIRED_PARAMETER = 'MISSING_REQUIRED_PARAMETER';
    const SEND_RESULT_PORTAL_AUTHENTICATION_FAILURE = 'PORTAL_AUTHENTICATION_FAILURE';
    const SEND_RESULT_MISSING_CONTENT = 'MISSING_CONTENT';
    const SEND_RESULT_CORRUPT_INPUT = 'CORRUPT_INPUT';
    const SEND_RESULT_TEMPLATE_RENDER_EXCEPTION = 'TEMPLATE_RENDER_EXCEPTION';
    const SEND_RESULT_GRAYMAIL_SUPPRESSED = 'GRAYMAIL_SUPPRESSED';
    const SEND_RESULT_UNCONFIGURED_SENDING_DOMAIN = 'UNCONFIGURED_SENDING_DOMAIN';
    const SEND_RESULT_UNDELIVERABLE = 'UNDELIVERABLE';
    const SEND_RESULT_CANCELLED_ABUSE = 'CANCELLED_ABUSE';
    const SEND_RESULT_QUARANTINED_ADDRESS = 'QUARANTINED_ADDRESS';
    const SEND_RESULT_ADDRESS_ONLY_ACCEPTED_ON_PROD = 'ADDRESS_ONLY_ACCEPTED_ON_PROD';
    const SEND_RESULT_PORTAL_NOT_AUTHORIZED_FOR_APPLICATION = 'PORTAL_NOT_AUTHORIZED_FOR_APPLICATION';
    const SEND_RESULT_ADDRESS_LIST_BOMBED = 'ADDRESS_LIST_BOMBED';
    const SEND_RESULT_ADDRESS_OPTED_OUT = 'ADDRESS_OPTED_OUT';
    const SEND_RESULT_RECIPIENT_FATIGUE_SUPPRESSED = 'RECIPIENT_FATIGUE_SUPPRESSED';
    const SEND_RESULT_TOO_MANY_RECIPIENTS = 'TOO_MANY_RECIPIENTS';
    const SEND_RESULT_PREVIOUSLY_UNSUBSCRIBED_BRAND = 'PREVIOUSLY_UNSUBSCRIBED_BRAND';
    const SEND_RESULT_NON_MARKETABLE_CONTACT = 'NON_MARKETABLE_CONTACT';
    const STATUS_PENDING = 'PENDING';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_COMPLETE = 'COMPLETE';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getSendResultAllowableValues()
    {
        return [
            self::SEND_RESULT_SENT,
            self::SEND_RESULT_IDEMPOTENT_IGNORE,
            self::SEND_RESULT_QUEUED,
            self::SEND_RESULT_IDEMPOTENT_FAIL,
            self::SEND_RESULT_THROTTLED,
            self::SEND_RESULT_EMAIL_DISABLED,
            self::SEND_RESULT_PORTAL_SUSPENDED,
            self::SEND_RESULT_INVALID_TO_ADDRESS,
            self::SEND_RESULT_BLOCKED_DOMAIN,
            self::SEND_RESULT_PREVIOUSLY_BOUNCED,
            self::SEND_RESULT_EMAIL_UNCONFIRMED,
            self::SEND_RESULT_PREVIOUS_SPAM,
            self::SEND_RESULT_PREVIOUSLY_UNSUBSCRIBED_MESSAGE,
            self::SEND_RESULT_PREVIOUSLY_UNSUBSCRIBED_PORTAL,
            self::SEND_RESULT_INVALID_FROM_ADDRESS,
            self::SEND_RESULT_CAMPAIGN_CANCELLED,
            self::SEND_RESULT_VALIDATION_FAILED,
            self::SEND_RESULT_MTA_IGNORE,
            self::SEND_RESULT_BLOCKED_ADDRESS,
            self::SEND_RESULT_PORTAL_OVER_LIMIT,
            self::SEND_RESULT_PORTAL_EXPIRED,
            self::SEND_RESULT_PORTAL_MISSING_MARKETING_SCOPE,
            self::SEND_RESULT_MISSING_TEMPLATE_PROPERTIES,
            self::SEND_RESULT_MISSING_REQUIRED_PARAMETER,
            self::SEND_RESULT_PORTAL_AUTHENTICATION_FAILURE,
            self::SEND_RESULT_MISSING_CONTENT,
            self::SEND_RESULT_CORRUPT_INPUT,
            self::SEND_RESULT_TEMPLATE_RENDER_EXCEPTION,
            self::SEND_RESULT_GRAYMAIL_SUPPRESSED,
            self::SEND_RESULT_UNCONFIGURED_SENDING_DOMAIN,
            self::SEND_RESULT_UNDELIVERABLE,
            self::SEND_RESULT_CANCELLED_ABUSE,
            self::SEND_RESULT_QUARANTINED_ADDRESS,
            self::SEND_RESULT_ADDRESS_ONLY_ACCEPTED_ON_PROD,
            self::SEND_RESULT_PORTAL_NOT_AUTHORIZED_FOR_APPLICATION,
            self::SEND_RESULT_ADDRESS_LIST_BOMBED,
            self::SEND_RESULT_ADDRESS_OPTED_OUT,
            self::SEND_RESULT_RECIPIENT_FATIGUE_SUPPRESSED,
            self::SEND_RESULT_TOO_MANY_RECIPIENTS,
            self::SEND_RESULT_PREVIOUSLY_UNSUBSCRIBED_BRAND,
            self::SEND_RESULT_NON_MARKETABLE_CONTACT,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_CANCELED,
            self::STATUS_COMPLETE,
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
        $this->container['event_id'] = $data['event_id'] ?? null;
        $this->container['status_id'] = $data['status_id'] ?? null;
        $this->container['send_result'] = $data['send_result'] ?? null;
        $this->container['requested_at'] = $data['requested_at'] ?? null;
        $this->container['started_at'] = $data['started_at'] ?? null;
        $this->container['completed_at'] = $data['completed_at'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['status_id'] === null) {
            $invalidProperties[] = "'status_id' can't be null";
        }
        $allowedValues = $this->getSendResultAllowableValues();
        if (!is_null($this->container['send_result']) && !in_array($this->container['send_result'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'send_result', must be one of '%s'",
                $this->container['send_result'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['status'] === null) {
            $invalidProperties[] = "'status' can't be null";
        }
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($this->container['status']) && !in_array($this->container['status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'status', must be one of '%s'",
                $this->container['status'],
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
     * Gets event_id
     *
     * @return \HubSpot\Client\Marketing\Transactional\Model\EventIdView|null
     */
    public function getEventId()
    {
        return $this->container['event_id'];
    }

    /**
     * Sets event_id
     *
     * @param \HubSpot\Client\Marketing\Transactional\Model\EventIdView|null $event_id event_id
     *
     * @return self
     */
    public function setEventId($event_id)
    {
        $this->container['event_id'] = $event_id;

        return $this;
    }

    /**
     * Gets status_id
     *
     * @return string
     */
    public function getStatusId()
    {
        return $this->container['status_id'];
    }

    /**
     * Sets status_id
     *
     * @param string $status_id Identifier used to query the status of the send.
     *
     * @return self
     */
    public function setStatusId($status_id)
    {
        $this->container['status_id'] = $status_id;

        return $this;
    }

    /**
     * Gets send_result
     *
     * @return string|null
     */
    public function getSendResult()
    {
        return $this->container['send_result'];
    }

    /**
     * Sets send_result
     *
     * @param string|null $send_result Result of the send.
     *
     * @return self
     */
    public function setSendResult($send_result)
    {
        $allowedValues = $this->getSendResultAllowableValues();
        if (!is_null($send_result) && !in_array($send_result, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'send_result', must be one of '%s'",
                    $send_result,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['send_result'] = $send_result;

        return $this;
    }

    /**
     * Gets requested_at
     *
     * @return \DateTime|null
     */
    public function getRequestedAt()
    {
        return $this->container['requested_at'];
    }

    /**
     * Sets requested_at
     *
     * @param \DateTime|null $requested_at Time when the send was requested.
     *
     * @return self
     */
    public function setRequestedAt($requested_at)
    {
        $this->container['requested_at'] = $requested_at;

        return $this;
    }

    /**
     * Gets started_at
     *
     * @return \DateTime|null
     */
    public function getStartedAt()
    {
        return $this->container['started_at'];
    }

    /**
     * Sets started_at
     *
     * @param \DateTime|null $started_at Time when the send began processing.
     *
     * @return self
     */
    public function setStartedAt($started_at)
    {
        $this->container['started_at'] = $started_at;

        return $this;
    }

    /**
     * Gets completed_at
     *
     * @return \DateTime|null
     */
    public function getCompletedAt()
    {
        return $this->container['completed_at'];
    }

    /**
     * Sets completed_at
     *
     * @param \DateTime|null $completed_at Time when the send was completed.
     *
     * @return self
     */
    public function setCompletedAt($completed_at)
    {
        $this->container['completed_at'] = $completed_at;

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
     * @param string $status Status of the send request.
     *
     * @return self
     */
    public function setStatus($status)
    {
        $allowedValues = $this->getStatusAllowableValues();
        if (!in_array($status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'status', must be one of '%s'",
                    $status,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['status'] = $status;

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


