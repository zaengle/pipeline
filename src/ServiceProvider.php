<?php

namespace Zaengle\Pipeline;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('pipeline', function ($app) {
            return new Pipeline($app);
        });
    }

    public function provides(): array
    {
        return ['pipeline'];
    }
}
