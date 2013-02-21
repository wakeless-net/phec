<?php
class DifferentExpectation extends \Phec\Expectation {}

describe("it", function() {
  $this->describe("with a different class_name", ["class_name" => "DifferentExpectation"], function() {
    $this->it("checks that this has the correct class name", function() {
      $this->assertInstanceOf("DifferentExpectation", $this);
    });

    $this->describe("with a nested context", function() {
      $this->it("checks that this has the correct class name", function() {
        $this->assertInstanceOf("DifferentExpectation", $this);
      });
    });
  });

  $this->it("should have a nested name", function() {
    $this->expects($this->getName())->equals("it\n\rshould have a nested name");
  });
});

