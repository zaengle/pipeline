<?php

namespace Zaengle\Pipeline\Tests\Pipes;

use Zaengle\Pipeline\Contracts\PipeInterface;

/**
 * Class TestPipe.
 */
class TestPipe implements PipeInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($traveler, \Closure $next)
    {
        return $next($traveler);
    }
}
