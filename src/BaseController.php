<?php

namespace SMSkin\LaravelSupport;

use SMSkin\LaravelSupport\Traits\RequestTrait;

abstract class BaseController
{
    use RequestTrait;

    protected mixed $result = null;

    /**
     * BaseAction constructor.
     *
     * @param BaseRequest|null $request
     */
    final public function __construct(protected ?BaseRequest $request = null)
    {
        if (!is_null($this->request)) {
            $this->validateRequest();
        }
    }

    abstract public function execute(): static;

    public function getResult(): mixed
    {
        return $this->result;
    }
}
