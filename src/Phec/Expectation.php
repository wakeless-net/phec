<?php

namespace Phec;

use Phec\Expectation\Pending;

/**
 * @abstract
 */
class Expectation extends \PHPUnit_Framework_TestCase {
  private $name;
  private $block;

  function __construct($name, $block = null, $parent = null) {
    $this->name = $name;
    $this->block = $block;
  }

  function toString() {
    return $this->name;
  }

  function runTest() {
    if($this->block) {
      $block = $this->block->bindTo($this);
      $block();
    } else {
      throw new Pending($this->name);
    }
  }

}
