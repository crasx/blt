<?php

namespace Crasx\Blt\Robo\Commands;

use Robo\Symfony\ConsoleIO;
use Robo\Tasks;

/**
 * Defines commands in the "docker" namespace.
 */
class DockerCommands extends Tasks {
    use NuvoleWeb\Robo\Task\Config\loadTasks;

  /**
   * Build docker image.
   *
   * @command docker:build
   * @description This is an example command.
   */
  public function buildContainer($options = ['tag' => TRUE, 'push' => FALSE]) {

    $dockerfile = $this->config->get('k8s.container.dockerfile') ?? $this->config->get('repo.root') . '/vendor/crasx/drupal-k8s/docker/Dockerfile';
    $image = $this->config->get('k8s.container.image') ?? throw new \Exception('No image set (k8s.container.image)');
    $phpVersion = $this->config->get('k8s.config.php.version') ?? throw new \Exception('No php version set (k8s.config.php.version)');
    $buildArgs = $this->config->get('k8s.container.build_args') ?? [];

    $cmd = $this->taskExec('docker build ' . $this->config->get('repo.root'))
      ->option('file', $dockerfile)
      ->option('build-arg', 'PHP_VERSION=' . $phpVersion);

    if ($this->config->get('k8s.config.memcache')) {
      $cmd->option('build-arg', 'MEMCACHE_SERVER_ARG=127.0.0.1:11211');
    }

    array_map(fn($k, $v) => $cmd->option('build-arg', "$k=$v"), array_keys($buildArgs), array_values($buildArgs));

    if ($options['tag']) {
      $tag = $this->config->get('k8s.container.tag') ?? throw new \Exception('No tag set (k8s.container.tag)');
      $cmd->option("tag", "$image:$tag");
    }

    if ($options['push']) {
      $cmd->option('push');
    }

    $cmd->run();

  }

  /**
   * @command kube:pods
   * @aliases pods
   */
  public function pods() {
    return $this->taskExec("kubectl")
      ->rawArg('--namespace ' . $this->config->get('kube.namespace'))
      ->rawArg("get pods")
      ->run();
  }

  public function shell(ConsoleIO $io) {

  }

  /**
   * @command kube:deploy
   * @aliases deploy
   */
  public function deploy(ConsoleIO $io) {

  }

}
