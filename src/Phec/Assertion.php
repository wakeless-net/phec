<?php

namespace Phec;

class Assertion {
  protected $subject;
  protected $test;
  function __construct($subject, $test) {
    $this->subject = $subject;
    $this->test = $test;
  }

  function __call($name, $args) {
    $name = ucwords($name);
    array_unshift($args, $this->subject);

    return call_user_func_array(array($this->test, "assert$name"), $args);
  }


}
