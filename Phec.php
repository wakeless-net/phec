<?php

require_once "Phec/ExampleGroup.php";
require_once "Phec/Expectation.php";
require_once "Phec/Expectation/Pending.php";

class Phec {
  static public $top_level_groups = [];
}

function describe($name, $block=null) {
  return Phec::$top_level_groups[] = new \Phec\ExampleGroup($name, $block);
}


