<?php

namespace Jfadich\JsonProperty;

trait JsonPropertyTrait
{
    /**
     * Service to manage multiple properties on a single model
     * @var JsonManager
     */
    private $jsonManager = null;

    /**
     * Retrieve the raw JSON string from the model
     *
     * @param $property
     * @return null|string
     * @throws JsonPropertyException
     */
    public function getJsonString($property)
    {
        $this->jsonManager()->isJsonProperty($property, true);

        return $this->{$property};
    }

    /**
     * Set the raw json string on the model
     *
     * @param string $property
     * @param string $jsonString
     * @throws JsonPropertyException
     */
    public function saveJsonString($property, $jsonString)
    {
        $this->jsonManager()->isJsonProperty($property, true);

        $this->{$property} = $jsonString;
    }


    /**
     * Magic method used to enable calling the property to access the JSON object
     *
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if($this->jsonManager()->isJsonProperty($method)) {
            array_unshift($arguments, $method);
            return call_user_func_array([$this->jsonManager(), 'getJsonProperty'], $arguments);
        }

        return is_callable(['parent', '__call']) ? parent::__call($method, $arguments) : null;
    }

    /**
     * Get the JsonManager instance
     *
     * @return JsonManager
     */
    private function jsonManager()
    {
        if($this->jsonManager === null)
            $this->jsonManager = new JsonManager($this, $this->jsonProperty);

        return $this->jsonManager;
    }

}