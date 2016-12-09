<?php

namespace Jfadich\JsonProperty;

/**
 * Class JsonProperty
 *
 * @package Jfadich\JsonProperty
 * @author John Fadich
 */
class JsonProperty
{
    /**
     * Array of current values
     *
     * @var array
     */
    protected $data = [ ];

    /**
     * Property on the model to store the JSON string
     *
     * @var string
     */
    private $boundProperty = null;

    /**
     * @param JsonPropertyInterface $model
     * @param $property
     */
    public function __construct( JsonPropertyInterface &$model, $property )
    {
        $this->boundProperty = $property;
        $data                = json_decode( $model->getJsonString($property), true );
        $this->data          = is_array($data) ? $data : [];
        $this->model         = $model;
    }

    /**
     * Push a value into an array assigned to the provided key.
     *
     * @param $key
     * @param $value
     * @return mixed
     * @throws JsonPropertyException
     */
    public function push($key, $value)
    {
        $array = $this->get($key, []);

        if(!is_array($array))
            throw new JsonPropertyException('Cannot push value to non array');

        array_push($array, $value);

        return $this->set($key, $array);
    }

    /**
     * Merge the given array into existing values. Require whitelist to add keys
     *
     * @param array $values
     * @param array $allowedKeys
     * @return array
     */
    public function merge( array $values, array $allowedKeys = [] )
    {
        $this->data = array_merge(
            $this->data,
            array_only( $values, array_merge(array_keys( $this->data ), $allowedKeys) )
        );

        $this->persist();

        return $this;
    }

    public function sort($sortColumn)
    {
        if($this->has($sortColumn)) {
            $this->data = array_sort($this->get($sortColumn, []), function($value, $key) {
                return $key;
            });

            $this->persist();
        }

        return $this;
    }

    /**
     * @param $key
     * @param string $default
     * @return mixed
     */
    public function get( $key, $default = null )
    {
        return array_get( $this->data, $key, $default );
    }

    /**
     * @param $key
     * @param $value
     */
    public function set( $key, $value )
    {
        array_set($this->data, $key, $value);
        $this->persist();

        return $value;
    }

    /**
     * Remove element from settings array
     *
     * @param $key
     */
    public function forget($key)
    {
        if($this->has($key)) {
            array_forget($this->data, $key);
        }

        $this->persist();
    }

    /**
     * @param $key
     * @return bool
     */
    public function has( $key )
    {
        if( !array_has( $this->data, $key ) || $this->isEmpty($key))
            return false;

        return true;
    }

    /**
     * @return object
     */
    public function all()
    {
        return (object)$this->data;
    }

    /**
     * Check if there is a value for the given key
     *
     * @param $key
     * @return bool
     */
    public function isEmpty($key)
    {
        return $this->data[$key] === '' || $this->data[$key] === null || empty($this->data[$key]);
    }

    /**
     * Save the model
     */
    private function persist()
    {
        $this->model->saveJsonString($this->boundProperty, json_encode($this->data));
    }
}