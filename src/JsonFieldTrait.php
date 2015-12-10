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

    private $jsonManager = null;


    /**
     * Retrieve the raw JSON string from the model
     *
     * @param $property
     * @return null|string
     * @throws Exception
     */
    public function getJsonString($property)
    {
        if ( !$this->jsonManager()->isJsonProperty($property) ) {
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
        if ( !$this->jsonManager()->isJsonProperty($property) ) {
            throw new Exception( 'jsonField is not set on model '. get_class($this) );
        }

        $this->{$property} = $jsonString;
    }


    /**
     * Magic method used to enable using the field name to access this object
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if($this->jsonManager()->isJsonProperty($method)) {
            return call_user_func_array([$this->jsonManager(), 'getJsonProperty'], array_unshift($arguments, $method));
        }

        return parent::__call($method, $arguments);
    }

    private function jsonManager()
    {
        if($this->jsonManager === null)
            $this->jsonManager = new JsonManager($this, $this->jsonField);

        return $this->jsonManager;
    }

}