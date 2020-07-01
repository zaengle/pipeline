<?php

namespace Zaengle\Pipeline\Example\Pipes;

use Zaengle\Pipeline\Contracts\PipeInterface;

/**
 * Class ExamplePipe.
 */
class ExamplePipe implements PipeInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($traveler, \Closure $next)
    {
        dump($traveler->getName());

        return $next($traveler);
    }
}
