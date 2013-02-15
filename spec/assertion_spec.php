<?php

use Phec\Assertion;

describe("assertions", function() {
  $this->describe("#expects", function() {
    $this->it("should return an assertion class", function() {
      $this->assertEquals(new Assertion("hello", $this), $this->expects("hello"));
    });
  });

  $this->describe("#expects", function() {
    $this->it("should defer its work to a test case", function() {

      $stub = $this->getMock("stdClass", ["assertEquals"]);

      $stub->expects($this->once())->method("assertEquals")->with("subject", "test");
      (new Assertion("subject", $stub))->equals("test");
    });

    $this->it("should fail when an assertion fails", function() {
      $result = describe("a test", function() {
        $this->it("should fail", function() {
          $this->expects("test")->contains("beep");
        });
      })->run();

      $this->assertFalse($result->wasSuccessful());
    });

    $this->it("should handle raising of exceptions", function() {
      $this->expects(function() {
        throw new Exception("Yep");
      })->to_raise_error(new Exception("Yep"));
    });
  });



});
