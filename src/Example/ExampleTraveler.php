<?php

namespace Zaengle\Pipeline\Example;

use Zaengle\Pipeline\Contracts\AbstractTraveler;

/**
 * Class ExampleTraveler.
 */
class ExampleTraveler extends AbstractTraveler {
  /**
   * @var mixed
   */
  private $demoData;

  /**
   * @return mixed
   */
  public function getDemoData()
  {
    return $this->demoData;
  }

  /**
   * @param mixed $demoData
   * @return ExampleTraveler
   */
  public function setDemoData($demoData)
  {
    $this->demoData = $demoData;

    return $this;
  }
}
