<?php

namespace Phec;

use Phec\Expectation\Pending;

class Expectation {
  private $name;
  private $block;

  function __construct($name, $block = null, $parent = null) {
    $this->name = $name;
    $this->block = $block;
  }

  function run() {
    if($this->block) {
      $block = $this->block;
      $block();
    } else {
      throw new Pending($this->name);
    }
  }


}
