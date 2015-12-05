<?php

namespace Jfadich\JsonField;

use Exception;

class JsonField
{
    /**
     * Model to present from
     *
     * @var Model
     */
    protected $model;

    /**
     * Field on the model to save data to
     *
     * @var string
     */
    private $field;

    /**
     * Array of current values
     *
     * @var array
     */
    protected $settings = [ ];

    /**
     * @param Model $model
     * @param string $field
     * @throws Exception
     */
    public function __construct( &$model, $field )
    {
        if ( !property_exists( $model, $field ) ) {
            throw new Exception( 'json_field is not set.' );
        }

        $this->field    = $field;
        $this->settings = $model->{$field} ?: [];
        $this->model    = $model;
    }

    /**
     * Merge the given array into existing values. Do not allow adding fields here
     *
     * @param array $attributes
     */
    public function merge( array $attributes )
    {
        $this->settings = array_merge(
            $this->settings,
            array_only( $attributes, array_keys( $this->settings ) )
        );

        $this->persist();
    }

    /**
     * @param $key
     * @param string $default
     * @return mixed
     */
    public function get( $key, $default = null )
    {
        return array_get( $this->settings, $key, $default );
    }

    /**
     * @param $key
     * @param $value
     */
    public function set( $key, $value )
    {
        $this->settings[ $key ] = $value;
        $this->persist();
    }

    /**
     * Remove element from settings array
     *
     * @param $key
     */
    public function forget($key)
    {
        if($this->has($key)) {
            unset($this->settings[$key]);
        }

        $this->persist();
    }

    /**
     * @param $key
     * @return bool
     */
    public function has( $key )
    {
        return array_key_exists( $key, $this->settings );
    }

    /**
     * @return array|mixed
     */
    public function all()
    {
        return $this->settings;
    }

    /**
     * Save the model
     */
    protected function persist()
    {
        $this->model->{$this->field} = $this->settings;

        $this->model->save();
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