<?php

namespace Phec;

use Phec\Expectation\Pending;

/**
 * @abstract
 */
class Expectation extends \PHPUnit_Framework_TestCase {
  private $name;
  private $block;
  private $parent;

  function __construct($name, $block = null, $parent = null) {
    $this->name = $name;
    $this->block = $block;
    $this->parent = $parent;
  }

  function toString() {
    return $this->name;
  }

  function __get($variable) {
    return $this->parent->$variable;
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
