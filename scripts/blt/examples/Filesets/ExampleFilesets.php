<?php

namespace Example\Blt\Plugin\Filesets;

// Do not remove this, even though it appears to be unused.
// @codingStandardsIgnoreLine
use Crasx\Blt\Annotations\Fileset;
use Crasx\Blt\Robo\Config\ConfigAwareTrait;
use Robo\Contract\ConfigAwareInterface;

/**
 * Class Filesets.
 *
 * Each fileset in this class should be tagged with a @fileset annotation and
 * should return \Symfony\Component\Finder\Finder object.
 *
 * @package Crasx\Blt\Custom
 * @see \Crasx\Blt\Robo\Filesets\Filesets
 */
class ExampleFilesets implements ConfigAwareInterface {
  use ConfigAwareTrait;

}
