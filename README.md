![apollo launch control](apollo-launch.jpg)
![Tests](https://github.com/zaengle/pipeline/workflows/Tests/badge.svg)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/zaengle/pipeline.svg?style=flat-square)](https://packagist.org/packages/zaengle/pipeline)
[![Total Downloads](https://img.shields.io/packagist/dt/zaengle/pipeline.svg?style=flat-square)](https://packagist.org/packages/zaengle/pipeline)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)


# Zaengle Pipeline
After using Laravel Pipelines to [handle complex data flows](https://zaengle.com/blog/handling-complex-data-flows) in our projects we saw a few patterns emerge:

- Database transactions
- Pipe interface
- Responses and exception handling

This package adds niceties on top of the Laravel Pipeline and consolidates them into a single reusable location.

_FYI - See the "Example" directory for a more thorough example._ 

## Installation
`composer require zaengle/pipeline`

## Testing
`phpunit`

## Basic Class Example

A pipeline is a common pattern for breaking data, logic, and response/exceptions into three distinct elements. **Zaengle Pipeline** abstracts these parts into helpful classes and gives some structure to the underlying pattern. 

```php
<?php

namespace Zaengle\Pipeline\Example;

use Zaengle\Pipeline\Example\Pipes\ExamplePipe;
use Zaengle\Pipeline\Pipeline;

class ExamplePipeline {
    public function __invoke() 
    {
        $traveler = (new ExampleTraveler());
        
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
```
    
## Breaking it Down

#### Create a Traveler

The first step in using the Zaengle Pipeline is to create a Data Traveler class. 
```php
$traveler = (new ExampleTraveler())
    ->setDemoData([ 'name' => 'Zaengle Pipeline' ]);
```

While not required, by extending `Zaengle\Pipeline\Contracts\AbstractTraveler` you will inherit additional methods utilized in the `Zaengle\Pipeline\Pipeline` class.

```php
<?php

namespace Zaengle\Pipeline\Example;

use Zaengle\Pipeline\Contracts\AbstractTraveler;

class ExampleTraveler extends AbstractTraveler {
}
```

Within the `$traveler` you may set any data required and it will be available within any of the pipes.

```php
class ExampleTraveler extends AbstractTraveler {
  private $demoData;

  public function getDemoData()
  {
    return $this->demoData;
  }

  public function setDemoData($demoData)
  {
    $this->demoData = $demoData;

    return $this;
  }
}
```

#### Pipes

Separate your business logic into appropriate "pipes," each of which should implement the `Zaengle\Pipeline\Contracts\PipeInterface`.

```php
<?php

namespace Zaengle\Pipeline\Example\Pipes;

use Zaengle\Pipeline\Contracts\PipeInterface;

class ExamplePipe implements PipeInterface
{
  public function handle($traveler, \Closure $next)
  {
    // Run your application logic here
    return $next($traveler);
  }
}
```

#### Primary Pipeline
Once you have your data and pipes established, send them through the `Zaengle\Pipeline\Pipeline` `->pipe()` method. 

`pipe()` accepts three parameters, two of which are required. The first parameter should be your `$traveler`, the second is your array of pipes, and the third, optional parameter tells `Pipeline` whether to use transactions or not.

```php
// use Zaengle\Pipeline\Pipeline;

$response = app(Pipeline::class)->pipe($traveler, $pipes, $useTransactions = true);
```

#### Results
Assuming the traveler extends `AbstractTraveler`, after sending the `$traveler` through the data pipes you will have access to a `->passed()` method which indicates whether the pipeline completed successfully or not. 

```php
$response = app(Pipeline::class)->pipe($traveler, $pipes, $useTransactions = true);

if ($response->passed()) {
  // Handle pass
  dump($response->getMessage());
} else {
  // Handle fail
  // $response->getException();
  // $response->getMessage();
  // $response->getStatus();
}
```

Extending `AbstractTraveler` also grants you access to the following convenience methods:

 *`$response->passed()`*
 
A boolean to indicate whether the traveler made it all the way through the pipes without any exceptions.

 *`$response->getStatus()`*
 
A string you can set with `->setStatus()` or will automatically be either 'ok' or 'fail'.

*`$response->getException()`*

To abort the process you may throw an exception which Pipeline will capture on the response. It will also set the status and message giving you access to `$response->getMessage()`.

*`$response->setMessage()`*

A `$message` property will automatically be set in the case of an exception. Otherwise, you can set it at any point during the pipeline execution.

*`$response->getMessage()`*

A string that is available upon the completion of the pipeline.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Header Image](https://www.flickr.com/photos/nasacommons/4858567220/)
