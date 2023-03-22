<?php

namespace SMSkin\LaravelSupport\Traits;

use SMSkin\LaravelSupport\Contracts\Arrayable;
use SMSkin\LaravelSupport\Contracts\Serializable;
use SMSkin\LaravelSupport\Models\SerializedObject;

trait SerializesModels
{
    use \Illuminate\Queue\SerializesModels {
        getSerializedPropertyValue as parentGetSerializedPropertyValue;
        getRestoredPropertyValue as parentGetRestoredPropertyValue;
    }

    protected function getSerializedPropertyValue($value)
    {
        if ($value instanceof Serializable) {
            return (new SerializedObject)
                ->setClass(get_class($value))
                ->setData($value->__serialize());
        }
        return $this->parentGetSerializedPropertyValue($value);
    }

    protected function getRestoredPropertyValue($value)
    {
        if ($value instanceof SerializedObject) {
            return $this->restoreSerializedArrayable($value);
        }
        return $this->parentGetRestoredPropertyValue($value);
    }

    private function restoreSerializedArrayable(SerializedObject $value): Arrayable
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
