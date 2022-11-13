<?php

namespace SMSkin\LaravelSupport\Models;

use SMSkin\LaravelSupport\Contracts\Arrayable;

class ModelField implements Arrayable
{
    protected string $field;
    protected mixed $oldValue;
    protected mixed $newValue;

    public function fromArray(array $data): static
    {
        return $this
            ->setField($data['field'])
            ->setOldValue($data['oldValue'])
            ->setNewValue($data['newValue']);
    }

    public function toArray(): array
    {
        return [
            'field' => $this->getField(),
            'oldValue' => $this->getOldValue(),
            'newValue' => $this->getNewValue()
        ];
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return ModelField
     */
    public function setField(string $field): self
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldValue(): mixed
    {
        return $this->oldValue;
    }

    /**
     * @param mixed $oldValue
     * @return ModelField
     */
    public function setOldValue(mixed $oldValue): self
    {
        $this->oldValue = $oldValue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewValue(): mixed
    {
        return $this->newValue;
    }

    /**
     * @param mixed $newValue
     * @return ModelField
     */
    public function setNewValue(mixed $newValue): self
    {
        $this->newValue = $newValue;
        return $this;
    }
}