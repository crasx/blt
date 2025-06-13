<?php

namespace Crasx\Blt\Robo\Common;

/**
 * Requires getters and setters for $this->executor.
 */
interface ExecutorAwareInterface {

  /**
   * Sets $this->executor.
   *
   * @param \Crasx\Blt\Robo\Common\Executor $executor
   *   Process executor.
   */
  public function setExecutor(Executor $executor);

  /**
   * Gets $this->executor.
   *
   * @return \Crasx\Blt\Robo\Common\Executor
   *   Process executor.
   */
  public function getExecutor();

}
