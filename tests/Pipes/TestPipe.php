<?php

namespace Zaengle\Pipeline\Tests\Pipes;

use Zaengle\Pipeline\Contracts\PipeInterface;

/**
 * Class TestPipe.
 */
class TestPipe implements PipeInterface
{
  /**
   * @inheritDoc
   */
  public function handle($traveler, \Closure $next)
  {
    return $next($traveler);
  }
}
