<?php

describe("let", function() {
  $this->let("variable", function() { return "letted variable"; });
  $this->it("should set an instance variable", function() {
    $this->assertEquals("letted variable", $this->variable);
  });
});

