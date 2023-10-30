<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace SMSkin\LaravelSupport;

abstract class BaseListener
{
    abstract public function handle(BaseEvent $event): void;
}
