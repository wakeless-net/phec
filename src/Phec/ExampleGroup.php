<?php

namespace Phec;
use Closure;

class ExampleGroup {
  public static $count = 0;

  private $name;
  protected $options = [];
  private $block;

  private $let_definitions = [];
  private $let_data = [];

  private $parent = null;

  private $expectations = [];

  function __construct($name, $options = array(), $block = null, $parent = null) {
    $this->name = $name;
    $this->parent = $parent;

    $this->options = $options;

    if($block) {
      $block = $block->bindTo($this);
      $block();
    }
  }

  function getParent() {
    return $this->parent;
  }

  private $contexts = [];

  function describe($name, $options = null, $function = null) {
    if(is_null($function) && $options instanceof Closure) {
      $function = $options;
      $options = [];
    } elseif(is_null($function) && is_null($options)) { 
      //do nothing
    }

    $group = new ExampleGroup($name, $options, $function, $this);
    $this->contexts[] = $group;
  }

  function it($name, $function = null) {
    $spec_class = @$this->options["class_name"] ?: "\Phec\Expectation";
    $it = new $spec_class($name, $function, $this);
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
