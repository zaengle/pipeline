<?php

namespace Zaengle\Pipeline\Tests\Pipes;

use Closure;
use Zaengle\Pipeline\Contracts\AbstractTraveler;
use Zaengle\Pipeline\Contracts\PipeInterface;
use Zaengle\Pipeline\Tests\TestTraveler;

class FailedTestPipe implements PipeInterface
{
    public function handle(TestTraveler|AbstractTraveler $traveler, Closure $next): AbstractTraveler
    {
        throw new \Exception('This Pipe Has Failed!!!');
    }
}
