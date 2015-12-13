<?php

namespace Jfadich\JsonProperty;

/**
 * This is a service that manages multiple JsonProperty instances for a single model
 *
 * @package Jfadich\JsonProperty
 * @author John Fadich
 */
class JsonManager
{
    /**
     * Array of instances of the JsonProperty object
     * @var array
     */
    private $jsonInstances = [];

    /**
     * Array of properties on the model that are bound to JsonProperty instances
     * @var array
     */
    private $properties = [];

    /**
     * Model to present from
     * @var JsonFieldInterface
     */
    protected $model = null;

    /**
     * @param JsonPropertyInterface $model
     * @param string $properties
     * @throws JsonPropertyException
     */
    public function __construct( JsonPropertyInterface &$model, $properties)
    {
        if(is_string($properties))
            $this->properties = [$properties];
        elseif(is_array($properties))
            $this->properties = $properties;
        else
            throw new JsonPropertyException('Invalid property list');

        $this->model = $model;
    }


    /**
     * Get instance for given property. If a key is given return the value or default.
     *
     * @param $property
     * @param string $key
     * @param mixed $default
     * @return JsonProperty
     * @throws JsonPropertyException
     */
    public function getJsonProperty($property, $key = null, $default = null)
    {
        $instance =  $this->getJsonInstance($property);

        if ( $key !== null ) {
            return $instance->get( $key, $default );
        }

        return $instance;
    }

    /**
     * Check if requested property is bound to the model
     *
     * @param string $property
     * @param bool $throwException
     * @return bool
     * @throws JsonPropertyException
     */
    public function isJsonProperty($property, $throwException = false)
    {
        if(is_string($property) && in_array($property, $this->properties))
            return true;

        if($throwException)
            throw new JsonPropertyException("Requested property '{$property}' is not a valid for '".get_class($this)."'.");

        return false;
    }

    /**
     * Instantiate the JsonProperty object, or return the existing instance
     *
     * @param string $property
     * @return JsonProperty
     * @throws JsonPropertyException
     */
    private function getJsonInstance($property)
    {
        if(!$this->isJsonProperty($property))
            throw new JsonPropertyException("Requested property '{$property}' is not a valid for '".get_class($this->model)."'.");

        if(!array_key_exists($property, $this->jsonInstances))
            $this->jsonInstances[$property] = new JsonProperty( $this->model, $property );

        return $this->jsonInstances[$property];
    }
}