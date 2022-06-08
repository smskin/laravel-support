<?php

namespace SMSkin\LaravelSupport;

use SMSkin\LaravelSupport\Traits\RequestTrait;
use Illuminate\Validation\ValidationException;

abstract class BaseController
{
    use RequestTrait;

    protected mixed $result = null;

    /**
     * BaseAction constructor.
     *
     * @param BaseRequest|null $request
     * @throws ValidationException
     */
    final public function __construct(?BaseRequest $request = null)
    {
        if ($request) {
            $this->request = $request;
            $this->validateRequest();
        }
    }

    abstract public function execute(): static;

    public function getResult(): mixed
    {
        return $this->result;
    }
}
