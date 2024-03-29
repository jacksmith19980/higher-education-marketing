<?php
/**
 * ExtensionActionDefinitionPatch
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  HubSpot\Client\Automation\Actions
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Custom Workflow Actions
 *
 * Create custom workflow actions
 *
 * The version of the OpenAPI document: v4
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.4.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace HubSpot\Client\Automation\Actions\Model;

use \ArrayAccess;
use \HubSpot\Client\Automation\Actions\ObjectSerializer;

/**
 * ExtensionActionDefinitionPatch Class Doc Comment
 *
 * @category Class
 * @description Fields on custom workflow action to be updated.
 * @package  HubSpot\Client\Automation\Actions
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null
 */
class ExtensionActionDefinitionPatch implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ExtensionActionDefinitionPatch';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'action_url' => 'string',
        'published' => 'bool',
        'input_fields' => '\HubSpot\Client\Automation\Actions\Model\InputFieldDefinition[]',
        'object_request_options' => '\HubSpot\Client\Automation\Actions\Model\ObjectRequestOptions',
        'input_field_dependencies' => 'OneOfSingleFieldDependencyConditionalSingleFieldDependency[]',
        'labels' => 'array<string,\HubSpot\Client\Automation\Actions\Model\ActionLabels>',
        'object_types' => 'string[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'action_url' => null,
        'published' => null,
        'input_fields' => null,
        'object_request_options' => null,
        'input_field_dependencies' => null,
        'labels' => null,
        'object_types' => null
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
        'action_url' => 'actionUrl',
        'published' => 'published',
        'input_fields' => 'inputFields',
        'object_request_options' => 'objectRequestOptions',
        'input_field_dependencies' => 'inputFieldDependencies',
        'labels' => 'labels',
        'object_types' => 'objectTypes'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'action_url' => 'setActionUrl',
        'published' => 'setPublished',
        'input_fields' => 'setInputFields',
        'object_request_options' => 'setObjectRequestOptions',
        'input_field_dependencies' => 'setInputFieldDependencies',
        'labels' => 'setLabels',
        'object_types' => 'setObjectTypes'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'action_url' => 'getActionUrl',
        'published' => 'getPublished',
        'input_fields' => 'getInputFields',
        'object_request_options' => 'getObjectRequestOptions',
        'input_field_dependencies' => 'getInputFieldDependencies',
        'labels' => 'getLabels',
        'object_types' => 'getObjectTypes'
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
        $this->container['action_url'] = $data['action_url'] ?? null;
        $this->container['published'] = $data['published'] ?? null;
        $this->container['input_fields'] = $data['input_fields'] ?? null;
        $this->container['object_request_options'] = $data['object_request_options'] ?? null;
        $this->container['input_field_dependencies'] = $data['input_field_dependencies'] ?? null;
        $this->container['labels'] = $data['labels'] ?? null;
        $this->container['object_types'] = $data['object_types'] ?? null;
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
     * Gets action_url
     *
     * @return string|null
     */
    public function getActionUrl()
    {
        return $this->container['action_url'];
    }

    /**
     * Sets action_url
     *
     * @param string|null $action_url The URL that will accept an HTTPS request each time workflows executes the custom action.
     *
     * @return self
     */
    public function setActionUrl($action_url)
    {
        $this->container['action_url'] = $action_url;

        return $this;
    }

    /**
     * Gets published
     *
     * @return bool|null
     */
    public function getPublished()
    {
        return $this->container['published'];
    }

    /**
     * Sets published
     *
     * @param bool|null $published Whether this custom action is published to customers.
     *
     * @return self
     */
    public function setPublished($published)
    {
        $this->container['published'] = $published;

        return $this;
    }

    /**
     * Gets input_fields
     *
     * @return \HubSpot\Client\Automation\Actions\Model\InputFieldDefinition[]|null
     */
    public function getInputFields()
    {
        return $this->container['input_fields'];
    }

    /**
     * Sets input_fields
     *
     * @param \HubSpot\Client\Automation\Actions\Model\InputFieldDefinition[]|null $input_fields The list of input fields to display in this custom action.
     *
     * @return self
     */
    public function setInputFields($input_fields)
    {
        $this->container['input_fields'] = $input_fields;

        return $this;
    }

    /**
     * Gets object_request_options
     *
     * @return \HubSpot\Client\Automation\Actions\Model\ObjectRequestOptions|null
     */
    public function getObjectRequestOptions()
    {
        return $this->container['object_request_options'];
    }

    /**
     * Sets object_request_options
     *
     * @param \HubSpot\Client\Automation\Actions\Model\ObjectRequestOptions|null $object_request_options object_request_options
     *
     * @return self
     */
    public function setObjectRequestOptions($object_request_options)
    {
        $this->container['object_request_options'] = $object_request_options;

        return $this;
    }

    /**
     * Gets input_field_dependencies
     *
     * @return OneOfSingleFieldDependencyConditionalSingleFieldDependency[]|null
     */
    public function getInputFieldDependencies()
    {
        return $this->container['input_field_dependencies'];
    }

    /**
     * Sets input_field_dependencies
     *
     * @param OneOfSingleFieldDependencyConditionalSingleFieldDependency[]|null $input_field_dependencies A list of dependencies between the input fields. These configure when the input fields should be visible.
     *
     * @return self
     */
    public function setInputFieldDependencies($input_field_dependencies)
    {
        $this->container['input_field_dependencies'] = $input_field_dependencies;

        return $this;
    }

    /**
     * Gets labels
     *
     * @return array<string,\HubSpot\Client\Automation\Actions\Model\ActionLabels>|null
     */
    public function getLabels()
    {
        return $this->container['labels'];
    }

    /**
     * Sets labels
     *
     * @param array<string,\HubSpot\Client\Automation\Actions\Model\ActionLabels>|null $labels The user-facing labels for the custom action.
     *
     * @return self
     */
    public function setLabels($labels)
    {
        $this->container['labels'] = $labels;

        return $this;
    }

    /**
     * Gets object_types
     *
     * @return string[]|null
     */
    public function getObjectTypes()
    {
        return $this->container['object_types'];
    }

    /**
     * Sets object_types
     *
     * @param string[]|null $object_types The object types that this custom action supports.
     *
     * @return self
     */
    public function setObjectTypes($object_types)
    {
        $this->container['object_types'] = $object_types;

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


