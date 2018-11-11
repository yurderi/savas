<?php

namespace savas\Commands;

use CMS\Components\Collector\Vue;
use CMS\Components\Command;
use CMS\Components\Form\Form;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
    
    public function configure()
    {
        $this->setName('savas:config');

        $this->addArgument('key', InputOption::VALUE_REQUIRED);
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $form = Form::load('plugin_savas');
        $key  = $input->getArgument('key');
        $value = $form->{$key};

        echo json_encode($value);
    }

}