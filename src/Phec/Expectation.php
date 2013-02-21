<?php

namespace Phec;

use Phec\Expectation\Pending;
use Mockery;

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
    return $this->getName();
  }

  function getName($withData = true) {
    if($this->parent) {
      return $this->parent->getName()."\n\r".$this->name;
    } else {
      return $this->name;
    }

  }

  function runTest() {
    if($this->parent) {
      $this->parent->run_before($this);
    }
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

  function expects($subject) {
    return new Assertion($subject, $this);
  }

  function mock() {
    $args = func_get_args();
    return call_user_func_array(array("Mockery", "mock"), $args);
  }

}
