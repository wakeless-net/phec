<?php

namespace Phec;
use Closure;

class ExampleGroup {
  public static $count = 0;

  private $name;
  protected $options = [];
  private $block;

  private $let_definitions = [];

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

  function getName() {
    if($this->parent) {
      $name = $this->parent->getName();
      $join = (substr($this->name, 0, 1) == "#") ? "" : " ";
      return $this->parent->getName().$join.$this->name;
    } else {
      return $this->name;
    }
  }

  function getParent() {
    return $this->parent;
  }

  private $contexts = [];

  function context($name, $options = null, $function = null) {
    if(is_null($function) && $options instanceof Closure) {
      $function = $options;
      $options = [];
    } elseif(is_null($function) && is_null($options)) { 
      //do nothing
    }

    $group = new ExampleGroup($name, $options, $function, $this);
    $this->contexts[] = $group;
  }

  function describe($name, $options = null, $function = null) {
    return $this->context($name, $options, $function);
  }

  function get_class_name() {
    if(@$this->options["class_name"]) {
      $class_name = $this->options["class_name"];
    } else {
      if($this->parent) {
        $class_name = $this->parent->get_class_name();
      } else {
        $class_name = isset(\Phec::$config["class_name"]) ? \Phec::$config["class_name"] : "\Phec\Expectation";
      }
    }

    return $this->evaluate($class_name);
  }

  function evaluate($variable) {
    if($variable instanceof Closure) {
      $variable = $variable->bindTo($this);
      return $variable();
    } else {
      return $variable;
    }
  }

  function it($name, $function = null) {
    $spec_class = $this->get_class_name();
    $it = new $spec_class($name, $function, $this);
    $this->expectations[] = $it;
  }

  function xit($name, $function = null) {
    $this->expectations[] = new PendingExpectation($name, $this);
  }


  private $before = [];
  function before($block) {
    $this->before[] = $block;
  }

  function let($name, $variable) {
    $this->let_definitions[$name] = $variable;
  }

  function subject($variable) {
    $this->let("subject", $variable);
  }



  function get_let($name, $scope) {
    if($this->has_let($name)) {
      return $this->process_let($name, $scope);
    } else {
      if($this->getParent()) {
        return $this->getParent()->get_let($name, $scope);
      } else {
        return null;
      }
    }
  } 

  private function has_let($name) {
    return !!@$this->let_definitions[$name];
  }

  private function process_let($name, $scope) {
    $definition = @$this->let_definitions[$name];
    if($definition instanceof Closure) {
      $definition = $definition->bindTo($scope);
      return $definition();
    } elseif(is_object($definition)) {
      return clone $definition;
    } else {
      return $this->let_definitions[$name];
    }
  }

  public function run_before($spec) {
    if($this->getParent()) $this->getParent()->run_before($spec);
    foreach($this->before as $block) {
      $block = $block->bindTo($spec);
      $block();
    }
  }

  function createResult() {
    return new \PHPUnit_Framework_TestResult;
  }

  function run($result = null, $filter = false) {
    if(!$result) {
      $result = $this->createResult();
    }

    foreach($this->expectations as $spec) {
      if(!$filter || stripos($spec->getName(), $filter) !== false) {
        $spec->run($result, $filter);
      }
    }

    foreach($this->contexts as $context) {
      $context->run($result, $filter);
    }
    return $result;
  }

  function mock() {
    $args = func_get_args();
    return call_user_func_array(array("Mockery", "mock"), $args);
  }
}
