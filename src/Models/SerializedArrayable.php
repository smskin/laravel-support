<?php

namespace SMSkin\LaravelSupport\Models;

class SerializedArrayable
{
    public string $class;
    public array $data;

    /**
     * @param string $class
     * @return SerializedArrayable
     */
    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param array $data
     * @return SerializedArrayable
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
