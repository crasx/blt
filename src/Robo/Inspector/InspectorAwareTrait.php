<?php

namespace Crasx\Blt\Robo\Inspector;

/**
 * Adds getters and setters for $this->inspector.
 */
trait InspectorAwareTrait {

  /**
   * The inspector.
   *
   * @var \Crasx\Blt\Robo\Inspector\Inspector
   */
  private $inspector;

  /**
   * Sets $this->inspector.
   */
  public function setInspector(Inspector $inspector) {
    $this->inspector = $inspector;

    return $this;
  }

  /**
   * Gets $this->inspector.
   *
   * @return \Crasx\Blt\Robo\Inspector\Inspector
   *   The inspector.
   */
  public function getInspector() {
    return $this->inspector;
  }

}
