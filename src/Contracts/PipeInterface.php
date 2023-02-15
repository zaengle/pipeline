<?php

namespace Zaengle\Pipeline\Contracts;

use Closure;

interface PipeInterface
{
    public function handle(AbstractTraveler $traveler, Closure $next): AbstractTraveler;
}
