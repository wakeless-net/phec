<?php

class AMockClass {
  function a_method() {}
}

describe("mocking", function() {
  $this->it("should defer to Mockery", function() {
    $this->assertInstanceOf("\Mockery\MockInterface", $this->mock("stdClass"));
  });

  $this->it("should fail if expectation is not met", function() {
    $mock = $this->mock("AMockClass");
    $this->expects(describe("a failing spec", function() use ($mock) {
      $this->it("should fail cause the expectation is never called", function() use ($mock) {
        $mock->shouldReceive("a_method");
      });
    })->run()->wasSuccessful())->false();
  });
});
