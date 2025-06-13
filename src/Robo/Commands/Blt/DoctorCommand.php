<?php

namespace Crasx\Blt\Robo\Commands\Blt;

use Crasx\Blt\Robo\BltTasks;
use Crasx\Blt\Robo\Doctor\AcsfCheck;
use Crasx\Blt\Robo\Doctor\ComposerCheck;
use Crasx\Blt\Robo\Doctor\ConfigCheck;
use Crasx\Blt\Robo\Doctor\DbCheck;
use Crasx\Blt\Robo\Doctor\DrupalCheck;
use Crasx\Blt\Robo\Doctor\DrushCheck;
use Crasx\Blt\Robo\Doctor\FileSystemCheck;
use Crasx\Blt\Robo\Doctor\NodeCheck;
use Crasx\Blt\Robo\Doctor\PhpCheck;
use Crasx\Blt\Robo\Doctor\SettingsFilesCheck;
use Crasx\Blt\Robo\Doctor\SimpleSamlPhpCheck;
use Crasx\Blt\Robo\Doctor\WebUriCheck;
use Crasx\Blt\Robo\Exceptions\BltException;

/**
 * Defines doctor command.
 */
class DoctorCommand extends BltTasks {

  /**
   * Output table.
   *
   * @var string
   */
  protected $outputTable;

  /**
   * Whether passed.
   *
   * @var bool
   */
  protected $passed;

  /**
   * List of problems.
   *
   * @var array
   */
  protected $problems = [];

  /**
   * Inspects your local blt configuration for possible issues.
   *
   * @command blt:doctor
   *
   * @aliases doctor
   *
   * @launchWebServer
   *
   * @throws \Crasx\Blt\Robo\Exceptions\BltException
   */
  public function doctor() {
    $this->doctorCheck();
  }

  /**
   * Run checks.
   *
   * @command doctor:check
   *
   * @hidden
   *
   * @throws \Crasx\Blt\Robo\Exceptions\BltException
   */
  public function doctorCheck() {
    $status = $this->getInspector()->getStatus();
    $this->printArrayAsTable($status);

    $checks = [
      AcsfCheck::class,
      ComposerCheck::class,
      ConfigCheck::class,
      DbCheck::class,
      DrupalCheck::class,
      DrushCheck::class,
      FileSystemCheck::class,
      NodeCheck::class,
      PhpCheck::class,
      SettingsFilesCheck::class,
      SimpleSamlPhpCheck::class,
      WebUriCheck::class,
    ];

    $success = TRUE;
    foreach ($checks as $class) {
      /** @var \Crasx\Blt\Robo\Doctor\DoctorCheck $object */
      $object = new $class($this->getConfig(), $this->getInspector(), $this->getContainer()->get('executor'), $status);
      $object->performAllChecks();
      $this->problems = array_merge($this->problems, $object->getProblems());
      if ($object->wasErrorLogged()) {
        $success = FALSE;
      }
    }

    $this->printArrayAsTable($this->problems, ['Check', "Problem"]);
    if (!$success) {
      throw new BltException("BLT Doctor discovered one or more critical issues.");
    }
  }

}
