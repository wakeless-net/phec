<?php

\Phec::$config = ["class_name" => "TestExpectation"];

class TestExpectation extends \Phec\Expectation {}
class ClosureExpectation extends \Phec\Expectation {}

describe("configuration", function() {
  $this->it("should have the expectation class configured externall", function() {
    $this->expects($this)->instance_of("TestExpectation");
  });

  $this->describe("a closure class_name", ["class_name" => function() { return "ClosureExpectation"; }], function() {
    $this->it("should execute the closure", function() {
      $this->expects($this)->instance_of("ClosureExpectation");
    });
  });
});
