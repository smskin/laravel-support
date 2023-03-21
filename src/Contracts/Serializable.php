<?php

namespace SMSkin\LaravelSupport\Contracts;

interface Serializable
{
    public function __serialize();
    public function __unserialize(array $values);
}