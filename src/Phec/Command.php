<?php

namespace Phec;

class Command {
  public static function main($exit = TRUE)
  {
      $command = new self;
      return $command->run($_SERVER['argv'], $exit);
  }

  /**
   * @param array   $argv
   * @param boolean $exit
   */
  public function run(array $argv, $exit = TRUE)
  {
    $runner = new TestRunner;
    $runner->run($argv[1], []);
    
  }
}
