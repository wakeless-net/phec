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

  function runTest() {
    if($this->block) {
      $block = $this->block->bindTo($this);
      $block();
    } else {
      throw new Pending($this->name);
    }
  }

  private $let_data = [];

  function __get($name) {
    $variable = @$this->let_data[$name];

    if($variable) { 
      return $variable;
    } elseif($this->parent) {
      return $this->let_data[$name] = $this->parent->get_let($name, $this);
    } else {
      return null;
    }
  }

}
