<?php

namespace Zaengle\Pipeline\Tests\Pipes;

use Zaengle\Pipeline\Contracts\PipeInterface;

/**
 * Class FailedTestPipe.
 */
class FailedTestPipe implements PipeInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($traveler, \Closure $next)
    {
        throw new \Exception('This Pipe Has Failed!!!');

        return $next($traveler);
    }
}
