<?php

namespace Zaengle\Pipeline\Tests;

use Orchestra\Testbench\TestCase;

/**
 * Class PipelineTestCase.
 */
class PipelineTestCase extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
  }

  /**
   * @param \Illuminate\Foundation\Application $app
   *
   * @return array
   */
  protected function getPackageProviders($app)
  {
    return [\Zaengle\Pipeline\ServiceProvider::class];
  }

  /**
   * @param \Illuminate\Foundation\Application $app
   *
   * @return array
   */
  protected function getPackageAliases($app)
  {
    return ['Pipeline' => 'Zaengle\Pipeline\Facade'];
  }
}
