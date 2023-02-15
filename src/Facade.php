<?php

namespace Zaengle\Pipeline;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pipeline';
    }
}
