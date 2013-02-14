<?php

describe("let", function() {
  $this->let("variable", function() { return "letted variable"; });
  $this->it("should set an instance variable", function() {
    $this->assertEquals("letted variable", $this->variable);
  });

  $this->let("mock", function() { return $this->getMock("stdClass"); });
  $this->it("should access mock", function() { $this->assertInstanceOf("stdClass", $this->mock); });

  $this->describe("accessing lets within before", function() {
    $this->before(function() {
      if(!$this->mock) throw new Exception;
    });

    $this->it("is called so before is called", function() {
    });
  });
});

