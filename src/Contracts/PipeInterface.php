<?php

namespace Zaengle\Pipeline\Contracts;

/**
 * Interface PipeInterface.
 */
interface PipeInterface
{
    /**
     * @param $traveler
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($traveler, \Closure $next);
}
