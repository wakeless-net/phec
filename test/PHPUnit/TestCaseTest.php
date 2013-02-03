<?php

class TestCaseTest extends PHPUnit_Framework_TestCase {
  
  public function testSuccess() {
      $test = describe("this is a successful test", function() {
        $this->it("should run (no assertions should mean success)", function() {});
      });
      $result = $test->run();

      $this->assertEquals(0, $result->errorCount());
      $this->assertEquals(0, $result->failureCount());
      $this->assertEquals(1, count($result));
  }
}
