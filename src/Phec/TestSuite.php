<?php

namespace Phec;
use Phec;
use \PHPUnit_Framework_TestResult;
use \PHPUnit_Framework_TestSuite;

class TestSuite extends PHPUnit_Framework_TestSuite {
	static function suite() {
    return new self;
	}

  function run(PHPUnit_Framework_TestResult $result = null, $filter = false) {
    if(!$result) $result = new PHPUnit_Framework_TestResult;

    foreach(Phec::$top_level_groups as $context) {
      $context->run($result, $filter);
    }
  }
}

