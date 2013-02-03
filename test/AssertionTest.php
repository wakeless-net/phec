<?php

class AssertionTest extends PHPUnit_Framework_TestCase {
  function test_it_should_implement_assert_equals() {
    $result = describe("assert_equals_spec", function() {
      $this->it("should pass", function() {
        $this->assertEquals("bbb", "bbb");
      });
    })->run();

    $this->assertEquals(1, count($result));
  }

  function test_it_should_fail_with_a_failed_assertion() {
    $result = describe("assert_equals_spec", function() {
      $this->it("should fail", function() {
        $this->assertEquals("bbb", "aaa");
      });
    })->run();

    $this->assertEquals(1, $result->failureCount());
  }
}
