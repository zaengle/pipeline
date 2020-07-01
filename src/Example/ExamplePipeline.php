<?php

namespace Zaengle\Pipeline\Example;

use Zaengle\Pipeline\Example\Pipes\ExamplePipe;
use Zaengle\Pipeline\Pipeline;

/**
 * Class Example.
 */
class ExamplePipeline
{
    public function __invoke()
    {
        $traveler = (new ExampleTraveler())
      ->setDemoData([
          'name' => 'Zaengle',
      ]);

        $pipes = [
            ExamplePipe::class,
        ];

        $response = app(Pipeline::class)->pipe($traveler, $pipes, $useTransactions = true);

        if ($response->passed()) {
            // Handle pass
        } else {
            // Handle fail
      // $response->getException();
      // $response->getMessage();
      // $response->getStatus();
        }
    }
}
