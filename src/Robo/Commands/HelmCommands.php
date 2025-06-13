<?php

namespace Crasx\DrupalK8s\Robo\Plugin\Commands;

use Crasx\Blt\Robo\BltTasks;
use Composer\Autoload\ClassLoader;
use Grasmash\YamlExpander\YamlExpander;
use Psr\Log\NullLogger;
use Robo\Collection\CollectionBuilder;
use Robo\Symfony\ConsoleIO;
use Robo\Task\Base\Exec;
use Robo\Tasks;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Yaml\Yaml;

/**
 * Defines commands in the "custom" namespace.
 */
class HelmCommands extends Tasks {

  protected function buildCommand($verb) {

    $command = $this->taskExec("helm");
    $values_files = [];


    if (!empty($secrets = $this->config->get('k8s.helm.secrets'))) {
      $command->arg('secrets');
      $values_files[] = $secrets;
    }

    $command->arg($verb);

    $helm_release = $this->config->get('k8s.helm.release-name');
    $command->arg($helm_release);

    $helm_chart = $this->config->get('k8s.helm.chart');
    $command->arg($helm_chart);

    $namespace = $this->config->get('k8s.helm.namespace') ?? throw new \Exception("Unable to load namespace (k8s.helm.namespace)");
    $command->option('namespace', $namespace);

    // Additional values
    $additional_values = $this->config->get('k8s.helm.values') ?? [];
    if (is_string($additional_values)) {
      $values_files[] = $additional_values;
    } else if (is_array($additional_values) && !empty($additional_values)){
      $values_files = array_merge($values_files, $additional_values);
    }

    foreach ($values_files as $value) {
      $command->option('values', $value);
    }

    return $command;
  }

  /**
   * @command helm:upgrade
   */
  public function upgrade() {
    $command = $this->buildCommand('upgrade');
    $command->run();


  }

  /**
   * @command helm:install
   */
  public function install() {
    $command = $this->buildCommand('install');
    $command->option('create-namespace');
    $command->run();
  }


}
