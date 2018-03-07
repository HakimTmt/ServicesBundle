<?php

namespace Tmt\ServicesBundle\Services\AsyncService;

use Symfony\Component\Process\Process;

/**
 * call services in background
 *
 * @author adouiri@techmyteam.com
 * @author aaboulhaj@techmyteam.com
 */
class AsyncService {

    /**
     * @param string $root_dir
     */
    public function __construct($root_dir) {
        $this->console = $root_dir . '/../bin/console';
    }

    /**
     * @param array $args
     * @return int|null
     */
    public function asyncServiceCall($service, $method, $args = []) {

        $commandline = $this->createCommandString($service, $method, $args);
        echo $commandline;
        
        return $this->startProcess($commandline);
    }

    /**
     * @param array $args
     * @return int|null
     */
    public function syncServiceCall($service, $method, $args = []) {

        $commandline = $this->createCommandString($service, $method, $args);
        return $this->runProcess($commandline);
    }

    /**
     * @param string $service
     * @param string $method
     * @param array $params
     * @return string
     */
    protected function createCommandString($service, $method, $params) {
        
        $arguments = escapeshellarg(base64_encode(serialize($params)));
        return 'php ' . $this->console  . ' tmt:service:call ' . $service . '  ' . $method . ' --data=' . $arguments . '  &';
    }

    /**
     * @param string $commandline
     * @return int|null
     */
    protected function runProcess($commandline) {
        $process = new Process($commandline);
        $process->run();
        if ($process->isSuccessful() && $process->isTerminated()) {
            $process->stop();
        }
        return $process->getPid();
    }

    /**
     * @param string $commandline
     * @return int|null
     */
    protected function startProcess($commandline) {
        $process = new Process($commandline);
        $process->start();
        if ($process->isSuccessful() && $process->isTerminated()) {
            $process->stop();
        }
        return $process->getPid();
    }

    public function test($test,$array) {
        $array[]=$test;
        return $array;
    }

}
