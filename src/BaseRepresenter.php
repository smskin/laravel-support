<?php

namespace SMSkin\LaravelSupport;

use Illuminate\Queue\SerializesModels;

abstract class BaseRepresenter
{
    use SerializesModels;

    abstract public function toArray(): array;
}
