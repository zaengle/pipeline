<?php

namespace Zaengle\Pipeline\Example;

use Zaengle\Pipeline\Contracts\AbstractTraveler;

class ExampleTraveler extends AbstractTraveler
{
    private array $demoData;

    public function getDemoData(): array
    {
        return $this->demoData;
    }

    public function setDemoData(array $demoData): ExampleTraveler
    {
        $this->demoData = $demoData;

        return $this;
    }
}
