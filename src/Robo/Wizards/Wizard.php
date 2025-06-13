<?php

namespace Crasx\Blt\Robo\Wizards;

use Crasx\Blt\Robo\BltTasks;
use Crasx\Blt\Robo\Common\Executor;
use Crasx\Blt\Robo\Common\IO;
use Crasx\Blt\Robo\Config\ConfigAwareTrait;
use Crasx\Blt\Robo\Inspector\InspectorAwareInterface;
use Crasx\Blt\Robo\Inspector\InspectorAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Robo\Contract\ConfigAwareInterface;
use Robo\Contract\IOAwareInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * This class should be used as the super class for all Wizards.
 *
 * @package Crasx\Blt\Robo\Wizards
 *
 * Wizards should take the following form:
 *   1. Evaluate a condition via an Inspector method.
 *   2. Prompt the the user to resolve invalid configuration or state.
 *   3. Perform tasks to resolve the issue.
 */
abstract class Wizard extends BltTasks implements ConfigAwareInterface, InspectorAwareInterface, IOAwareInterface, LoggerAwareInterface {

  use ConfigAwareTrait;
  use InspectorAwareTrait;
  use IO;
  use LoggerAwareTrait;

  /**
   * Process Executor.
   *
   * @var \Crasx\Blt\Robo\Common\Executor
   */
  protected $executor;

  /**
   * File system component.
   *
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $fs;

  /**
   * Inspector constructor.
   *
   * @param \Crasx\Blt\Robo\Common\Executor $executor
   *   Process executor.
   */
  public function __construct(Executor $executor) {
    $this->executor = $executor;
    $this->fs = new Filesystem();
  }

}
