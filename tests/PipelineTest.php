<?php

namespace Zaengle\Pipeline\Tests;

use Illuminate\Support\Facades\DB;
use Zaengle\Pipeline\Pipeline;
use Zaengle\Pipeline\Tests\Pipes\FailedTestPipe;
use Zaengle\Pipeline\Tests\Pipes\TestPipe;

/**
 * Class PipelineTest.
 */
class PipelineTest extends PipelineTestCase
{
  /** @test */
  public function it_successfully_process_a_pipeline()
  {
    $traveler = (new TestTraveler());

    $pipes = [
      TestPipe::class,
    ];

    $response = app(Pipeline::class)->pipe($traveler, $pipes);

    $this->assertEquals('ok', $response->getStatus());
    $this->assertEquals('Traveler passed successfully.', $response->getMessage());
    $this->assertNull($response->getException());
  }

  /** @test */
  public function it_fails_to_process_a_pipeline()
  {
    $traveler = (new TestTraveler());

    $pipes = [
      FailedTestPipe::class,
    ];

    $response = app(Pipeline::class)->pipe($traveler, $pipes);

    $this->assertEquals('fail', $response->getStatus());
    $this->assertEquals('This Pipe Has Failed!!!', $response->getMessage());
    $this->assertNotNull($response->getException());
  }

  /** @test */
  public function it_uses_db_transactions_on_a_successful_run()
  {
    DB::shouldReceive('beginTransaction')
      ->once()
      ->andReturnSelf()
      ->shouldReceive('commit')
      ->once();

    app(Pipeline::class)->pipe(
      new TestTraveler(),
      [TestPipe::class],
      true
    );
  }

  /** @test */
  public function it_uses_db_transactions_on_a_failed_run()
  {
    DB::shouldReceive('beginTransaction')
      ->once()
      ->andReturnSelf()
      ->shouldReceive('rollback')
      ->once();

    app(Pipeline::class)->pipe(
      new TestTraveler(),
      [FailedTestPipe::class],
      true
    );
  }

  /** @test */
  public function it_works_with_vanilla_travelers()
  {
    $traveler = new \stdClass();

    $pipes = [
      TestPipe::class,
    ];

    $response = app(Pipeline::class)->pipe($traveler, $pipes);

    $this->assertSame($traveler, $response);
  }
}
