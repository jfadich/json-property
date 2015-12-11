<?php

namespace Jfadich\JsonProperty;

interface JsonPropertyInterface
{
    /**
     * Get the raw JSON string off the Model.
     *
     * @param string $property
     * @return null|string
     */
    public function getJsonString($property);

    /**
     * Persist the updated JSON string
     *
     * @param string $property
     * @param string $jsonString
     * @return mixed
     */
    public function saveJsonString($property, $jsonString);
}