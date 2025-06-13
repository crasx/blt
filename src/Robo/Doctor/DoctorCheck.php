<?php

namespace Crasx\Blt\Robo\Doctor;

use Crasx\Blt\Robo\Common\Executor;
use Crasx\Blt\Robo\Common\ExecutorAwareInterface;
use Crasx\Blt\Robo\Common\ExecutorAwareTrait;
use Crasx\Blt\Robo\Config\ConfigAwareTrait;
use Crasx\Blt\Robo\Inspector\Inspector;
use Crasx\Blt\Robo\Inspector\InspectorAwareInterface;
use Crasx\Blt\Robo\Inspector\InspectorAwareTrait;
use Robo\Config\Config;
use Robo\Contract\ConfigAwareInterface;

/**
 * BLT Doctor checks.
 */
abstract class DoctorCheck implements ConfigAwareInterface, InspectorAwareInterface, ExecutorAwareInterface {
  use ConfigAwareTrait;
  use InspectorAwareTrait;
  use ExecutorAwareTrait;

  /**
   * Problems.
   *
   * @var array
   */
  protected $problems = [];

  /**
   * Whether an error was logged.
   *
   * @var bool
   */
  protected $errorLogged = FALSE;

  /**
   * Drush status.
   *
   * @var string
   */
  protected $drushStatus;

  /**
   * Constructor.
   */
  public function __construct(
    Config $config,
    Inspector $inspector,
    Executor $executor,
    $drush_status,
  ) {
    $this->setConfig($config);
    $this->setInspector($inspector);
    $this->drushStatus = $drush_status;
    $this->executor = $executor;
  }

  /**
   * Log problem.
   */
  public function logProblem($check, $message, $type) {
    if (is_array($message)) {
      $message = implode("\n", $message);
    }
    $reflection = new \ReflectionClass($this);
    $class_name = $reflection->getShortName();
    $label = "<$type>$class_name:$check</$type>";
    $this->problems[$label] = $message;

    if ($type == 'error') {
      $this->errorLogged = TRUE;
    }
  }

  /**
   * Was error logged.
   *
   * @return bool
   *   Was error logged.
   */
  public function wasErrorLogged() {
    return $this->errorLogged;
  }

  /**
   * Perform all checks.
   *
   * @return array
   *   Array.
   */
  abstract public function performAllChecks();

  /**
   * Get problems.
   *
   * @return array
   *   Problems?
   */
  public function getProblems() {
    return $this->problems;
  }

}
