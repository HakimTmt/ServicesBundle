<?php

namespace Tmt\ServicesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;

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
                ->setName('command:service:call')
                ->setDescription('Calls a service method with arguments')
                ->addOption('data', null, InputOption::VALUE_OPTIONAL, 'Arguments to supply to the method');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $argsData = $input->getOption('data');
        $data = unserialize(base64_decode($argsData));

        $log = "Process :  - " . date("F j, Y, H:i:s ") . PHP_EOL .
                "-------------------------" . PHP_EOL;

        file_put_contents('./mailLog.txt', $log, FILE_APPEND);
    }

}
