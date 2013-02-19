<?php

namespace Phec;

class Assertion {
  protected $subject;
  protected $test;
  function __construct($subject, $test) {
    $this->subject = $subject;
    $this->test = $test;
  }

  static function from_snake_case($str) {
    $str = preg_replace("/_/", " ", $str);
    return $str;
  }

  static function to_camel_case($str) {
    $str = ucwords($str);
    return preg_replace("/\s/", "", $str);
  }


  function __call($name, $args) {
    $name = self::to_camel_case(self::from_snake_case($name));
    array_push($args, $this->subject);

    return call_user_func_array(array($this->test, "assert$name"), $args);
  }

  function to_raise_error($exception) {
    try {
      $subject = $this->subject;
      $subject();
      $this->assertTrue(false, "this should raise an exception $e");
    } catch(\Exception $e) {
      $this->test->assertEquals($exception, $e);
    }
  }


}
