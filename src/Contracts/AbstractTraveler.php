<?php

namespace Zaengle\Pipeline\Contracts;

use Exception;

abstract class AbstractTraveler
{
    const TRAVELER_SUCCESS = 'ok';

    const TRAVELER_FAIL = 'fail';

    protected string $status;

    protected string $message = 'Traveler passed successfully.';

    protected ?Exception $exception = null;

    public function setStatus(string $status): AbstractTraveler
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setMessage(string $message): AbstractTraveler
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setException(Exception $exception): AbstractTraveler
    {
        $this->exception = $exception;

        return $this;
    }

    public function getException(): ?Exception
    {
        return $this->exception;
    }

    public function passed(): bool
    {
        return $this->getStatus() === self::TRAVELER_SUCCESS;
    }
}
