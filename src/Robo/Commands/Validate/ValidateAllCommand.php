<?php

namespace Crasx\Blt\Robo\Commands\Validate;

use Crasx\Blt\Robo\BltTasks;

/**
 * Defines commands in the "validate*" namespace.
 */
class ValidateAllCommand extends BltTasks {

  /**
   * Runs all code validation commands.
   *
   * @command validate
   * @hidden
   *
   * @throws \Crasx\Blt\Robo\Exceptions\BltException
   */
  public function all() {
    return $this->invokeNamespace('validate');
  }

}
