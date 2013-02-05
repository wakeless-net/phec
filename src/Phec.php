<?php

class Phec {
  static public $top_level_groups = [];
}

function describe($name, $options = null, $block=null) {
  if(is_null($block) && $options instanceof Closure) {
    $block = $options;
    $options = [];
  }

  return Phec::$top_level_groups[] = new \Phec\ExampleGroup($name, $options, $block);
}


