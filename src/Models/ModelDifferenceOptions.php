<?php

namespace SMSkin\LaravelSupport\Models;

class ModelDifferenceOptions
{
    public array $logExceptAttributes = [];

    public array $attributeRawValues = [];

    /**
     * Start configuring model with the default options.
     */
    public static function defaults(): self
    {
        return new static();
    }

    /**
     * Exclude these attributes from being logged.
     */
    public function logExcept(array $attributes): self
    {
        $this->logExceptAttributes = $attributes;

        return $this;
    }

    /**
     * Exclude these attributes from being casted.
     */
    public function useAttributeRawValues(array $attributes): self
    {
        $this->attributeRawValues = $attributes;

        return $this;
    }
}