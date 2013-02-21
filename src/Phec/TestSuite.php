<?php

namespace Phec;
use Phec;
use \PHPUnit_Framework_TestResult;
use \PHPUnit_Framework_TestSuite;

class TestSuite extends PHPUnit_Framework_TestSuite {
	static function suite() {
    return new self;
	}

  function run(PHPUnit_Framework_TestResult $result = null) {
    if(!$result) $result = new PHPUnit_Framework_TestResult;
    $result->addListener(new \Mockery\Adapter\Phpunit\TestListener());

    foreach(Phec::$top_level_groups as $context) {
      $context->run($result);
    }
  }
}

