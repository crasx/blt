<?php

namespace Crasx\DrupalK8s\Blt\Plugin\Commands;

use Crasx\Blt\Robo\BltTasks;
use Composer\Autoload\ClassLoader;
use Grasmash\YamlExpander\YamlExpander;
use Psr\Log\NullLogger;
use Robo\Symfony\ConsoleIO;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Yaml\Yaml;

/**
 * Defines commands in the "custom" namespace.
 */
class KubeCommands extends BltTasks {

  /**
   * @param string $out
   * @param $matches
   *
   * @return mixed
   */
  public function selectPod() {
    $this->say("Gathering pods");
    $pods = $this->taskExec("kubectl")
      ->rawArg("get pods")
      ->option('namespace', $this->config->get('k8s.helm.namespace'))
      ->option('selector', 'app.kubernetes.io/instance=' . $this->config->get('k8s.helm.release-name'))
      ->option('output', 'json')
      ->printOutput(FALSE)
      ->run()
    ->getMessage();

    $pods = json_decode($pods, TRUE);

    if (sizeof($pods['items'] ?? []) > 0) {
      $selected = 0;
      $pod_options= [];
        foreach ($pods['items'] as $item){
          $pod_options[$item['metadata']['name']] =  $item['metadata']['name'] . ' - ' .  $item['status']['phase'] . ' - ' . $item['metadata']['creationTimestamp'];
        }
      if (sizeof($pods['items']) > 1) {
        $selected = $this->askChoice('Select a pod', $pod_options);
      } else {
        $selected = array_key_first($pod_options);
      }

      return $selected;
    }
    throw new \Exception("Unable to find pod");
  }

  /**
   * @command kube:shell
   */
  public function shell (ConsoleIO $io) {

    $pod = $this->selectPod();

    return $this->taskExec("kubectl")
      ->interactive()
      ->arg('exec')
      ->option('namespace', $this->config->get('k8s.helm.namespace'))
      ->arg($pod)
      ->option('stdin')
      ->option('tty')
      ->rawArg('-- bash')
      ->run();
  }

  /**
   * @command kube:logs
   */
  public function logs (ConsoleIO $io) {

    $pod = $this->selectPod();

    return $this->taskExec("kubectl")
      ->interactive()
      ->arg('logs')
      ->option('namespace', $this->config->get('k8s.helm.namespace'))
      ->arg($pod)
      ->run();
  }

  /**
   * @command kube:deploy-restart
   */
  public function restartDeployment(ConsoleIO $io) {
    return $this->taskExec("kubectl")
      ->rawArg('rollout restart deployment drupal-drupal-k8s')
      ->option('namespace', $this->config->get('k8s.helm.namespace'))
      ->run();

  }

  }
