<?php

namespace Zaengle\Pipeline;

/**
 * Class Facade.
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'pipeline';
    }
}
