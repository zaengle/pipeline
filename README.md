![Tests](https://github.com/zaengle/pipeline/workflows/Tests/badge.svg?branch=master)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/zaengle/pipeline.svg?style=flat-square)](https://packagist.org/packages/zaengle/pipeline)
[![Total Downloads](https://img.shields.io/packagist/dt/zaengle/pipeline.svg?style=flat-square)](https://packagist.org/packages/zaengle/pipeline)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

![apollo launch control](apollo-launch.jpg)

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

A pipeline is a common pattern for breaking data, logic, and response/exceptions into three distinct elements. **Zaengle Pipeline** abstracts these parts into helpful classes and gives some structure to the underlying pattern. For example, let's explore at what a pipeline might look like for a ficticious user registration:

```php
<?php

use App\RegisterTraveler;
use App\Pipes\CreateUser;
use App\Pipes\HandleMailingList;
use Zaengle\Pipeline\Pipeline;

class FicticiousRegisterController {
    public function __invoke() 
    {
        $traveler = (new RegisterTraveler())->setRequest(request()->all());
        
        $pipes = [
            CreateUser::class,
            HandleMailingList::class,
            // any other items that need to happen during registration...
        ];
    
        $response = app(Pipeline::class)->pipe($traveler, $pipes, $useTransactions = true);
    
        if ($response->passed()) {
            return response()->json('Welcome!');
        } else {
            return response()->json('Your registration failed with the following error: ' . $response->getMessage());
        }
    }
}
```
    
## Breaking it Down

#### Create a Traveler

The first step in using the Zaengle Pipeline is to create a Data Traveler class. _Note: The `setRequest()` method is contrived for this example._
```php
$traveler = (new RegisterTraveler())->setRequest(request()->all());
```

While not required, by extending `Zaengle\Pipeline\Contracts\AbstractTraveler` you will inherit additional methods utilized in the `Zaengle\Pipeline\Pipeline` class.

```php
<?php
use Zaengle\Pipeline\Contracts\AbstractTraveler;

class RegisterTraveler extends AbstractTraveler {
}
```

Within the `$traveler` you may set any data required and it will be available within any of the pipes.

```php
<?php
use Zaengle\Pipeline\Contracts\AbstractTraveler;

class RegisterTraveler extends AbstractTraveler {

  private $request;

  private $user;
    // custom methods
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
```

#### Pipes

Separate your business logic into appropriate "pipes," each of which should implement the `Zaengle\Pipeline\Contracts\PipeInterface`.

```php
<?php

use App\User;
use Zaengle\Pipeline\Contracts\PipeInterface;

class CreateUser implements PipeInterface {
    public function handle($traveler, \Closure $next)
    {
        $traveler->setUser(
            User::create([
                'email' => $traveler->getRequest()->email,
                'password' => $traveler()->getRequest()->password,
            ])
        );
        
        return $next($traveler);
    }
}
```

```php
<?php

use App\MailingService;
use Zaengle\Pipeline\Contracts\PipeInterface;

class HandleMailingList implements PipeInterface
{
    public function handle($traveler, \Closure $next)
    {
        if ($traveler->getRequest()->subscribe) {
            MailingService::subscribe($traveler->getUser()->email);
            
            $traveler->getUser()->update([
                'subscriber' => true,
            ]);
        }
        
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

## Testing Strategy

When testing a pipeline you should test the overall pipeline to make sure the given input matches the expected output.

```php
<?php
use TestCase;

class FicticiousRegistrationTest extends TestCase 
{
    /** @test */
    public function it_registers_a_user()
    {
        $userStub = factory(User::class)->make();

        $response = $this->postJson('ficticious-endpoint', [
            'email' => $userStub->email,
            'password' => 'password',
            'subscribe' => true,
        ]);
        
        $response->assertJsonFragment('Welcome!');
    
        $this->assertDatabaseHas('users', [
            'email' => $userStub->email,
            'subscribed' => true,
        ]);  
    }
}
```

You may also want to test individual pipes like this:

```php
<?php

use TestCase;
use App\User;
use App\RegisterTraveler;
use App\Pipes\CreateUser;

class CreateUserTest extends TestCase {
    /** @test */
    public function it_creates_a_user()
    {
        $traveler = (new RegisterTraveler)->setRequest(['email' => 'test', 'password' => 'password']);
        
        (new CreateUser)->handle($traveler, function () {});
        
        $this->assertInstanceOf(User::class, $traveler->getUser());
    }
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Header Image](https://www.flickr.com/photos/nasacommons/4858567220/)
