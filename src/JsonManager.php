<?php

namespace Jfadich\JsonField;


class JsonManager
{

    /**
     * Instance of the JsonField object
     * @var JsonField
     */
    private $jsonInstances = null;

    private $properties = null;

    /**
     * Model to present from
     *
     * @var JsonFieldInterface
     */
    protected $model;

    public function __construct( JsonFieldInterface &$model, $properties)
    {
        if(is_string($properties))
            $this->properties = [$properties];
        elseif(is_array($properties))
            $this->properties = $properties;

        $this->model = $model;
    }


    public function getJsonProperty($property, $key = null, $default = null)
    {
        $instance =  $this->getJsonInstance($property);

        if ( $key !== null ) {
            return $instance->get( $key, $default );
        }

        return $instance;
    }


    public function isJsonProperty($property)
    {
        if(in_array($property, $this->properties))
            return true;

        return false;
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
        if(!$this->isJsonProperty($property))
            throw new Exception('Invalid property');

        if(in_array($property, $this->properties)) {
            if(array_key_exists($property, $this->jsonInstances))
                return $this->jsonInstances[$property];
            else
                return $this->jsonInstances[$property] = new JsonField( $this->model );
        } else {
            throw new Exception('Invalid JsonField property');
        }

        return $this->jsonInstance[$property];
    }
}