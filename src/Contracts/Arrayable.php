<?php

namespace SMSkin\LaravelSupport\Contracts;

use Illuminate\Contracts\Support\Arrayable as BaseContract;

interface Arrayable extends BaseContract
{
    public function fromArray(array $data): static;

    public function toArray(): array;
}