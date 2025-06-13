<?php

namespace Crasx\Blt\Robo\Commands\Tests;

use Crasx\Blt\Robo\BltTasks;

/**
 * Defines commands in the "tests" namespace.
 */
class TestsAllCommand extends BltTasks {

  /**
   * Runs all tests, including Behat, PHPUnit, and security updates check.
   *
   * @command tests
   *
   * @throws \Crasx\Blt\Robo\Exceptions\BltException
   */
  public function tests() {
    return $this->invokeNamespace('tests');
  }

}
