<?php

class BeforeTest extends PHPUnit_Framework_TestCase {
  function test_before_should_be_executed() {
    $mock = $this->getMock("stdClass", ["before"]);
    $mock->expects($this->once())->method("before");

    describe("before spec", function() use ($mock) {
      $this->before(function() use ($mock) {
        $mock->before();
      });
      $this->it("should execute", function() {});
    })->run();
  }

  function test_before_should_not_be_executed() {
    $mock = $this->getMock("stdClass", ["before"]);
    $mock->expects($this->never())->method("before");

    describe("before spec", function() use ($mock) {
      $this->before(function() use ($mock) {
        $mock->before();
      });
    })->run();
  }

  function test_before_should_be_executed_before_each_spec() {
    $mock = $this->getMock("stdClass", ["before"]);
    $mock->expects($this->exactly(2))->method("before");

    describe("before spec", function() use ($mock) {
      $this->before(function() use ($mock) {
        $mock->before();
      });
      $this->it("should execute an empty block", function () {});
      $this->it("should execute a second empty block", function () {});
    })->run();
  }

  function test_nested_befores() {
    $mock = $this->getMOck("stdClass", ["before"]);
    $mock->expects($this->at(0))->method("before")->with("top");
    $mock->expects($this->at(1))->method("before")->with("nested");

    describe("nested befores", function() use ($mock) {
      $this->before(function() use ($mock) {
        $mock->before("top");
      });

      $this->describe("nested", function() use ($mock) {
        $this->before(function() use ($mock) {
          $mock->before("nested");
        });
        $this->it("should force execution of befores", function() {});

      });
    })->run();
  }

  function test_multiple_befores() {
    $mock = $this->getMock("stdClass", ["before"]);
    $mock->expects($this->at(0))->method("before")->with("1st");
    $mock->expects($this->at(1))->method("before")->with("2nd");

    describe("multiple befores", function() use ($mock) {
      $this->before(function() use ($mock) {
        $mock->before("1st");
      });
      $this->before(function() use ($mock) {
        $mock->before("2nd");
      });
      $this->it("should execute", function () {});
    })->run();
  }

  function test_before_accesses_let() {
    $test = $this;
    describe("before using let", function() use ($test) {
      $this->let("var", 4);
      $this->before(function() use ($test) {
        $test->assertEquals(4, $this->var);
      });
      $this->it("should force this to be executed", function() {});
    })->run();
  }
}
