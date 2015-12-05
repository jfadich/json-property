<?php

namespace Jfadich\JsonField;

use Exception;

trait JsonFieldTrait
{
    /**
     * Instance of the JsonField object
     * @var JsonField
     */
    private $jsonInstance = null;

    /**
     * Get Json Object. If a key is given return associated value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getJson( $key = null, $default = null )
    {
        $instance = $this->getJsonInstance();

        if ( $key !== null ) {
            return $instance->get( $key, $default );
        }

        return $instance;
    }

    /**
     * Retrieve the raw JSON string from the model
     * @return null|string
     * @throws Exception
     */
    public function getJsonString()
    {
        if ( $this->jsonField === null ) {
            throw new Exception( 'jsonField is not set on model.' );
        }
        return $this->{$this->jsonField};
    }

    /**
     * Set the raw json string on the model and persist it
     * @param $jsonString
     * @throws Exception
     */
    public function saveJsonString($jsonString)
    {
        if ( $this->jsonField === null ) {
            throw new Exception( 'jsonField is not set on model '. get_class($this) );
        }

        $this->{$this->jsonField} = $jsonString;

        $this->save();
    }

    /**
     * Instantiate the JsonField object, or return the existing instance
     * @return JsonField
     */
    private function getJsonInstance()
    {
        if ( !$this->jsonInstance instanceof JsonField ) {
            $this->jsonInstance = new JsonField( $this );
        }

        return $this->jsonInstance;
    }

    /**
     * Magic method used to enable using the field name to access this object
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if($method === $this->jsonField) {
            return call_user_func_array([$this, 'getJson'], $arguments);
        }

        return parent::__call($method, $arguments);
    }
}