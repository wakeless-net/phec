<?php

require_once "Phec.php";

class GroupTest extends PHPUnit_Framework_TestCase {
  function setUp() {
    parent::setUp();
    \Phec::$top_level_groups = [];
  }

  function testItCanPass() {
    $mock = $this->getMock("stdClass", ["testRun"]);
    $mock->expects($this->once())->method("testRun");


    describe("this is a test that should pass", function() use($mock) {
      $this->it("should execute", function() use($mock) {
        $mock->testRun();
      });
    })->run();
  }

  /**
   * @expectedException Phec\Expectation\Pending
   */
  function test_empty_expectations_should_throw_a_pending_exception() {
    describe("an empty test", function() {
      $this->it("should be empty");
    })->run();
  }

  /**
   * @expectedException ANewException
   */
  function testItShouldRaiseAnException() {
    describe("this is a test that should raise an exception", function() {
      $this->it("raises an exception", function() {
        throw new ANewException;
      });
    })->run();
  }


  /**
   * @expectedException ANewException
   */
  function testItAllowsNestedDescribes() {
    describe("this is a test that is nested", function() {
      $this->describe("this is nested", function() {
        $this->it("raises an exception", function() {
          throw new ANewException;
        });
      });
    })->run();
  }

  function testItAllowsEmptyDescribes() {
    describe("Empty")->run();
  }

  function testItStoresTopLevelDescribes() {
    describe("This is a test that is described")->run();
    describe("This is a test that is described")->run();
    $this->assertEquals(2, count(\Phec::$top_level_groups));
  }

}

class Counter {
  static public $count = 0;
  function __construct() {
    if(self::$count++ > 0) throw new Exception;
  }
}

class ANewException extends Exception {}
