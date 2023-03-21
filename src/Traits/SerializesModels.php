<?php

namespace SMSkin\LaravelSupport\Traits;

use SMSkin\LaravelSupport\Contracts\Arrayable;
use SMSkin\LaravelSupport\Models\SerializedArrayable;

trait SerializesModels
{
    use \Illuminate\Queue\SerializesModels {
        getSerializedPropertyValue as parentGetSerializedPropertyValue;
        getRestoredPropertyValue as parentGetRestoredPropertyValue;
    }

    protected function getSerializedPropertyValue($value)
    {
        if ($value instanceof Arrayable) {
            return (new SerializedArrayable)
                ->setClass(get_class($value))
                ->setData($value->toArray());
        }
        return $this->parentGetSerializedPropertyValue($value);
    }

    protected function getRestoredPropertyValue($value)
    {
        if ($value instanceof SerializedArrayable) {
            return $this->restoreSerializedArrayable($value);
        }
        return $this->parentGetRestoredPropertyValue($value);
    }

    private function restoreSerializedArrayable(SerializedArrayable $value): Arrayable
    {
        /**
         * @var $class Arrayable
         */
        $class = $value->getClass();
        return (new $class)->fromArray($value->getData());
    }
}
