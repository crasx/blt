<?php

namespace Crasx\Blt\Robo\Inspector;

/**
 * Requires setter for inspector.
 */
interface InspectorAwareInterface {

  /**
   * Sets $this->inspector.
   *
   * @param \Crasx\Blt\Robo\Inspector\Inspector $inspector
   *   The inspector.
   *
   * @return $this
   *   The object.
   */
  public function setInspector(Inspector $inspector);

}
