<?php

namespace Jfadich\JsonField;

trait JsonFieldTrait
{
    /**
     * Instance of the Json Field object
     * @var null
     */
    private $jsonInstance = null;

    /**
     * Get Json Object. If key is given return associated value
     *
     * @param null $key
     * @param null $default
     * @return mixed|null|Settings
     * @throws Exception
     */
    public function getJson( $key = null, $default = null )
    {
        $instance = $this->getJsonInstance();

        if ( $key !== null ) {
            return $instance->get( $key, $default );
        }

        return $instance;
    }

    protected function getJsonInstance()
    {
        if ( $this->json_field === null ) {
            throw new Exception( 'json_field is not set on model.' );
        }

        if ( !$this->jsonInstance instanceof JsonField ) {
            $this->jsonInstance = new JsonField( $this, $this->json_field );
        }

        return $this->jsonInstance;
    }

    public function __call($method, $arguments)
    {
        // Defer to parent if the field is not set or is not being requested
        if($this->json_field === null || $method != $this->json_field)
            return parent::__call($method, $arguments);

        return call_user_func_array([$this, 'getJson'], $arguments);
    }
}