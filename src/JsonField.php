<?php

namespace Jfadich\JsonField;

use Exception;

class JsonField
{
    /**
     * Model to present from
     *
     * @var JsonFieldInterface
     */
    protected $model;

    /**
     * Array of current values
     *
     * @var array
     */
    protected $data = [ ];

    /**
     * @param JsonFieldInterface $model
     */
    public function __construct( JsonFieldInterface &$model )
    {
        $data           = json_decode( $model->getJsonString(), true );
        $this->data     = is_array($data) ? $data : [];
        $this->model    = $model;
    }

    /**
     * Merge the given array into existing values. Do not allow adding fields here
     *
     * @param array $values
     * @return array
     */
    public function merge( array $values )
    {
        $this->data = array_merge(
            $this->data,
            array_only( $values, array_keys( $this->data ) )
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
        return array_has( $this->data, $key );
    }

    /**
     * @return array|mixed
     */
    public function all()
    {
        return (object)$this->data;
    }

    /**
     * Save the model
     */
    private function persist()
    {
        $this->model->saveJsonString(json_encode($this->data));
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function __get( $key )
    {
        if ( $this->has( $key ) ) {
            return $this->get( $key );
        }

        throw new Exception( "The property {$key} does not exist." );
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set( $key, $value )
    {
        $this->set( $key, $value );
    }
}