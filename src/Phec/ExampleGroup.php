<?php

namespace Phec;
use Closure;

class ExampleGroup {
  public static $count = 0;

  private $name;
  private $block;

  private $let_definitions = [];
  private $let_data = [];

  private $parent = null;

  private $expectations = [];

  function __construct($name, $block = null, $parent = null) {
    $this->name = $name;
    $this->parent = $parent;

    if($block) {
      $block = $block->bindTo($this);
      $block();
    }
  }

  function getParent() {
    return $this->parent;
  }

  private $contexts = [];

  function describe($name, $function = null) {
    $group = new ExampleGroup($name, $function, $this);
    $this->contexts[] = $group;
  }

  function it($name, $function = null) {
    $it = new Expectation($name, $function);
    $this->expectations[] = $it;
  }


  private $before = [];
  function before($block) {
    $this->before[] = $block;
  }

  function let($name, $variable) {
    $this->let_definitions[$name] = $variable;
  }

  function __get($name) {
    $variable = @$this->let_data[$name];

    if($variable) { 
      return $variable;
    } else {
      if($this->has_let($name)) {
        return $this->let_data[$name] = $this->process_let($name);
      } else {
        if($this->getParent()) {
          return $this->getParent()->$name;
        } else {
          return null;
        }
      }
    }
  } 

  private function has_let($name) {
    return !!@$this->let_definitions[$name];
  }

  private function process_let($name) {
    $definition = @$this->let_definitions[$name];
    if($definition instanceof Closure) {
      return $definition();
    } elseif(is_object($definition)) {
      return clone $definition;
    } else {
      return $this->let_definitions[$name];
    }
  }

  protected function run_before() {
    if($this->getParent()) $this->getParent()->run_before();
    foreach($this->before as $block) {
      $block();
    }
  }

  function createResult() {
    return new \PHPUnit_Framework_TestResult;
  }

  function run($result = null) {
    if(!$result) {
      $result = $this->createResult();
    }

    foreach($this->expectations as $spec) {
      $this->let_data = [];
      $this->run_before();

      $spec->run($result);
    }

    foreach($this->contexts as $context) {
      $context->run($result);
    }
    return $result;
  }

}
