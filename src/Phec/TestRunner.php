<?php

namespace Phec;
use PHPUnit_Framework_TestResult;
use PHPUnit_TextUI_ResultPrinter;

class TestRunner extends \PHPUnit_TextUI_TestRunner {

  static function run($test_files, array $arguments = array()) {
    foreach($test_files as $test_file) {
      if(is_dir($test_file)) foreach(new \RecursiveDirectoryIterator($test_file) as $file) {
        if($file->isFile()) {
          if(substr($file->getBaseName(), 0-strlen("spec.php")) == "spec.php") {
            $wrap = new Wrapper($file->getPath()."/".$file->getFileName());
          }
        }
      } else {
        $wrap = new Wrapper($test_file);
      }
    }

    $result = new PHPUnit_Framework_TestResult;

    $printer = new PHPUnit_TextUI_ResultPrinter(
                  null,
                  !!$arguments["verbose"],
                  !$arguments["nocolor"],
                  !!$arguments["debug"]
                );
    $result->addListener($printer);
    
    $suite = new TestSuite;
    $suite->run($result, $arguments["filter"]);

    $printer->printResult($result);
  }

}
