<?php
class AlternateExpectation extends \Phec\Expectation {
}

describe("describe with alternate class", ["class_name" => "AlternateExpectation"], function() {
  $this->it("executes in the context of the specified class", function() {
    $this->assertInstanceOf("AlternateExpectation", $this);
  });
});

