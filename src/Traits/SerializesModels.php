<?php

namespace SMSkin\LaravelSupport\Traits;

use SMSkin\LaravelSupport\Contracts\Arrayable;
use SMSkin\LaravelSupport\Contracts\Serializable;
use SMSkin\LaravelSupport\Models\SerializedArrayable;

trait SerializesModels
{
    use \Illuminate\Queue\SerializesModels {
        getSerializedPropertyValue as parentGetSerializedPropertyValue;
        getRestoredPropertyValue as parentGetRestoredPropertyValue;
    }

    protected function getSerializedPropertyValue($value)
    {
        if ($value instanceof Arrayable && $value instanceof Serializable) {
            return (new SerializedArrayable)
                ->setClass(get_class($value))
                ->setData($value->__serialize());
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
        $class = $value->getClass();

        /**
         * @var $instance Serializable
         */
        $instance = (new $class);
        $instance->__unserialize($value->getData());
        return $instance;
    }
}
