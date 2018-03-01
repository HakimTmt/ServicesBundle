<?php

namespace Tmt\ServicesBundle\Services\Command;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Process\Process;

/**
 * Pagination service
 *
 * @author TMT
 */
class Command {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function asyncServiceCall($args = []) {

        $commandline = $this->createCommandString($args);       
        return $this->runProcess($commandline);
    }

    /**
     * @param string $service
     * @param string $method
     * @param array $arguments
     * @return string
     */
    protected function createCommandString($params) {
        $url = $this->container->getParameter('kernel.root_dir') . '/../';
        $arguments = escapeshellarg(base64_encode(serialize($params)));
        return 'php ' . $url . 'bin/console command:service:call  --data=' . $arguments . '  &';

    }

    /**
     * @param string $commandline
     * @return int|null
     */
    protected function runProcess($commandline) {
        $process = new Process($commandline);
        $process->start();
        if($process->isSuccessful() && $process->isTerminated()){
            $process->stop();
        }
        return $process->getPid();
    }

}
