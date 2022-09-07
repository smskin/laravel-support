<?php

namespace SMSkin\LaravelSupport\Traits;

use SMSkin\LaravelSupport\BaseRequest;
use SMSkin\LaravelSupport\Exceptions\InvalidRequestType;
use SMSkin\LaravelSupport\Exceptions\RequestNotInitialized;

trait RequestTrait
{
    protected ?BaseRequest $request;

    protected ?string $requestClass;

    protected function validateRequest(): void
    {
        if ($this->requestClass && !$this->request) {
            throw new RequestNotInitialized();
        }

        if ($this->requestClass && !($this->request instanceof $this->requestClass)) {
            throw new InvalidRequestType(
                sprintf(
                    "Invalid request type. Expected: %s, Received: %s. Action: %s",
                    $this->requestClass,
                    get_class($this->request),
                    static::class
                )
            );
        }
    }
}
