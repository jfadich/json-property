<?php

namespace Jfadich\JsonField;

use Exception;

trait JsonFieldTrait
{
    /**
     * Properties on model that are to be used as JSON objects
     *
     * @var string|array
     */
    protected $jsonField = null;

    /**
     * Instance of the JsonField object
     * @var JsonField
     */
    private $jsonInstances = null;

    /**
     * Get Json Object. If a key is given return associated value
     *
     * @param $property
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getJson( $property, $key = null, $default = null )
    {
        $instance = $this->getJsonInstance($property);

        if ( $key !== null ) {
            return $instance->get( $key, $default );
        }

        return $instance;
    }

    /**
     * Retrieve the raw JSON string from the model
     *
     * @param $property
     * @return null|string
     * @throws Exception
     */
    public function getJsonString($property)
    {
        if ( $this->jsonField === null ) {
            throw new Exception( 'jsonField is not set on model '. get_class($this) );
        }

        return $this->{$property};
    }

    /**
     * Set the raw json string on the model and persist it
     *
     * @param $property
     * @param $jsonString
     * @throws Exception
     */
    public function saveJsonString($property, $jsonString)
    {
        if ( $this->jsonField === null ) {
            throw new Exception( 'jsonField is not set on model '. get_class($this) );
        }

        $this->{$property} = $jsonString;
    }

    /**
     * Instantiate the JsonField object, or return the existing instance
     *
     * @param $property
     * @return JsonField
     * @throws Exception
     */
    private function getJsonInstance($property)
    {
        if(!$this->isValidProperty($property))
            throw new Exception('Invalid property');

        if(is_string($this->jsonField))
            $this->jsonField = [$this->jsonField];

        if(in_array($property, $this->jsonField)) {
            if(array_key_exists($property, $this->jsonInstances))
                return $this->jsonInstances[$property];
            else
                return $this->jsonInstances[$property] = new JsonField( $this );
        } else {
            throw new Exception('Invalid JsonField property');
        }

        return $this->jsonInstance[$property];
    }

    /**
     * Magic method used to enable using the field name to access this object
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if($this->isValidProperty($method)) {
            return call_user_func_array([$this, 'getJson'], array_unshift($arguments, $method));
        }

        return parent::__call($method, $arguments);
    }

    private function isValidProperty($property)
    {
        if( ! is_string($this->jsonField) || !is_array($this->jsonField) || !is_string($property))
            return false;

        if(is_string($this->jsonField) && $this->jsonField === $property)
            return true;

        if(in_array($property, $this->jsonField))
            return true;

        return false;
    }
}