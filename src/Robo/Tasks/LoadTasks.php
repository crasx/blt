<?php

namespace Crasx\Blt\Robo\Tasks;

/**
 * Load BLT's custom Robo tasks.
 */
trait LoadTasks {

  /**
   * Task drush.
   *
   * @return \Crasx\Blt\Robo\Tasks\DrushTask
   *   Drush task.
   */
  protected function taskDrush() {
    /** @var \Crasx\Blt\Robo\Tasks\DrushTask $task */
    $task = $this->task(DrushTask::class);
    /** @var \Symfony\Component\Console\Output\OutputInterface $output */
    $output = $this->output();
    $task->setVerbosityThreshold($output->getVerbosity());

    return $task;
  }

  /**
   * Task git.
   *
   * @param null|string $pathToGit
   *   Path to git.
   *
   * @return \Crasx\Blt\Robo\Tasks\GitTask
   *   Git task.
   */
  protected function taskGit($pathToGit = 'git') {
    return $this->task(GitTask::class, $pathToGit);
  }

  /**
   * Task phpunit.
   *
   * @param null|string $pathToPhpUnit
   *   Path to phpunit.
   *
   * @return \Crasx\Blt\Robo\Tasks\PhpUnitTask
   *   Phpunit task.
   */
  protected function taskPhpUnitTask($pathToPhpUnit = NULL) {
    return $this->task(PhpUnitTask::class, $pathToPhpUnit);
  }

  /**
   * Task run tests.
   *
   * @return \Crasx\Blt\Robo\Tasks\RunTestsTask
   *   run tests task.
   */
  protected function taskRunTestsTask($runTestsScriptCommand = NULL) {
    return $this->task(RunTestsTask::class, $runTestsScriptCommand);
  }

}
