<?php

namespace Zaengle\Pipeline\Contracts;

/**
 * Class AbstractTraveler.
 */
abstract class AbstractTraveler
{

  const TRAVELER_SUCCESS = 'ok';

  const TRAVELER_FAIL = 'fail';

  /**
   * @var string
   */
  protected $status;

  /**
   * @var string
   */
  protected $message;

  /**
   * @var \Exception
   */
  protected $exception;

  /**
   * @param mixed $status
   * @return AbstractTraveler
   */
  public function setStatus($status)
  {
    $this->status = $status;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * @param mixed $message
   * @return AbstractTraveler
   */
  public function setMessage($message)
  {
    $this->message = $message;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @param mixed $exception
   * @return AbstractTraveler
   */
  public function setException($exception)
  {
    $this->exception = $exception;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getException()
  {
    return $this->exception;
  }

  /**
   * @return bool
   */
  public function passed()
  {
    return $this->getStatus() === self::TRAVELER_SUCCESS;
  }
}
