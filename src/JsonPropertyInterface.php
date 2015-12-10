<?php

namespace Jfadich\JsonProperty;

interface JsonPropertyInterface
{
    /**
     * Get the raw JSON string off the Model.
     *
     * @return string|null
     */
    public function getJsonString();

    /**
     * Persist the updated JSON string
     *
     * @param $jsonString
     * @return mixed
     */
    public function saveJsonString($jsonString);
}