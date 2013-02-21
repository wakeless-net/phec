<?php

describe("mocking", function() {
  $this->it("should defer to Mockery", function() {
    $this->assertInstanceOf("\Mockery\MockInterface", $this->mock("stdClass"));
  });
});
