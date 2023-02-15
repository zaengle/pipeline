<?php

namespace Zaengle\Pipeline\Example\Pipes;

use Closure;
use Zaengle\Pipeline\Contracts\AbstractTraveler;
use Zaengle\Pipeline\Contracts\PipeInterface;
use Zaengle\Pipeline\Example\ExampleTraveler;

class ExamplePipe implements PipeInterface
{
    public function handle(ExampleTraveler|AbstractTraveler $traveler, Closure $next): AbstractTraveler
    {
        dump($traveler->getName());

        return $next($traveler);
    }
}
