<?php

namespace Tmt\ServicesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CallServiceCommand
 * @package Tmt\ServicesBundle\Command
 */
class CallServiceCommand extends ContainerAwareCommand {

    /**
     * @inheritdoc
     */
    protected function configure() {
        $this
                ->setName('tmt:service:call')
                ->setDescription('Calls a service method with arguments')
                ->addArgument('service', InputArgument::REQUIRED, 'Service ID')
                ->addArgument('method', InputArgument::REQUIRED, 'Method to call on the service')
                ->addOption('data', null, InputOption::VALUE_OPTIONAL, 'Arguments to supply to the method');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $serviceId = $input->getArgument('service');
        $service = $this->getContainer()->get($serviceId);
        $method = $input->getArgument('method');

        $argsData = $input->getOption('data');
        $data = unserialize(base64_decode($argsData));
        if ($argsData) {
            $res = call_user_func_array([$service, $method], $data);
        } else {
            $res = call_user_func([$service, $method]);
        }
        $log = "Process : " . PHP_EOL
                . "    Service: $serviceId" . PHP_EOL
                . "    Method: $method " . PHP_EOL
                . "    params: " . print_r($data, TRUE) . PHP_EOL
                . "    res: " . print_r($res, TRUE) . PHP_EOL
                . "    Date: " . date("F j, Y, H:i:s ") . PHP_EOL .
                "-------------------------" . PHP_EOL;
        $root_dir = $this->getContainer()->getParameter('kernel.logs_dir') ;
        file_put_contents($root_dir . '/serviceLog.log', $log, FILE_APPEND);
    }

}
