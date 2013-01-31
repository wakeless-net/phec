<?php

require_once "Phec.php";

class LetTest extends PHPUnit_Framework_TestCase {
  function testLet() {
    $test = $this;
    describe("let", function() use($test) {
      $this->let("var", "bbb");
      $this->it("access let", function() use($test) {
        $test->assertEquals("bbb", $this->var);
      });
    })->run();
  }

  function testLetBlock() {
    $test = $this;
    describe("let", function() use ($test) {
      $this->let("var", function() { return "bbb"; });
      $this->it("access let", function() use ($test) {
        $test->assertEquals("bbb", $this->var);
      });
    })->run();
  }

  function testLetsAreAvailableInNestedBlocks() {
    $test = $this;
    describe("let", function() use ($test) {
      $this->let("var", "bbb");
      $this->describe("in nested", function () use($test) {
        $this->it("checks let", function() use ($test) {
          $test->assertEquals("bbb", $this->var);
        });
      });
    })->run();
  }

  function testLetIsOnlyCalledOnce() {
    $test = $this;
    describe("let", function() use ($test) {
      $count = 1;
      $this->let("var", function() use ($count) { return new Counter; });
      $this->it("accesses let", function() use ($test) {
        $test->assertThat($this->var, $test->identicalTo($this->var));
      });
    })->run();
  }

  function test_lets_should_be_reset_between_tests() {
    $test = $this;

    describe("let", function() use ($test) {
      $this->let("holder", new Holder);
      $this->it("changes a variable on holder", function() {
        $this->holder->held = 3;
      });
      $this->it("should have the holder variable back to the start", function() use ($test) {
        $test->assertEquals(1, $this->holder->held);
      });
    })->run();
    
  }

}

class Holder {
  public $held = 1;
}
