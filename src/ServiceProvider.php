<?php

namespace Zaengle\Pipeline;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('pipeline', function ($app) {
      return new Pipeline($app);
    });
  }

  /**
   * @return array
   */
  public function provides()
  {
    return [
      'pipeline'
    ];
  }
}
