<?php

describe("subject", function() {
  $this->context("basic variable", function() {

    $this->subject("bbbb");
    $this->it("should be able to access the subject", function() {
      $this->expects($this->subject)->equals("bbbb");
    });
  });
  $this->context("function", function() {
    $this->subject(function() { return "aaaa"; });
    $this->it("should have access to the evaled subject", function() {
      $this->expects($this->subject)->equals("aaaa");
    });
  });

});
