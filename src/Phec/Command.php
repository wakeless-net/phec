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
    $processedArgs = $this->processArguments();

    $args = $processedArgs->getArgumentValues();
    $flags = $processedArgs->getFlagValues();

    $runner = new TestRunner;
    $runner->run($args[0], $flags);

  }

  function processArguments() {
    $cmd = new \Commando\Command;
    $cmd->argument()->require()->describedAs("The spec(s) you would like to run");
    $cmd->option("nocolor")->boolean()->describedAs("Used to remove color from the outputs.");
    $cmd->option("verbose")->boolean()->describedAs("Verbose output of tests.");
    $cmd->option("debug")->boolean()->describedAs("Output debugging information.");
    return $cmd;
  }
}
