<?php

namespace Phec;
use PHPUnit_Framework_TestResult;
use PHPUnit_TextUI_ResultPrinter;

class TestRunner extends \PHPUnit_TextUI_TestRunner {

  static function run($test, array $arguments = array()) {
    $wrap = new Wrapper($test);

    $result = new PHPUnit_Framework_TestResult;

    $printer = new PHPUnit_TextUI_ResultPrinter(
                  null,
                  !!$arguments["verbose"],
                  !$arguments["nocolor"],
                  !!$arguments["debug"]
                );


    $result->addListener($printer);
    
    $suite = new TestSuite;
    $suite->run($result);

    $printer->printResult($result);
  }

}
