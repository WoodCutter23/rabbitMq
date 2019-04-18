<?php

namespace App\ConsoleCommands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RabbitDeamonCommand extends Command
{
    protected $listener;
    protected $connection;

    public function __construct($rabbitListener, $rabbitConnection)
    {
        parent::__construct();
        $this->listener = $rabbitListener;
        $this->connection = $rabbitConnection;
    }

    protected function configure()
    {
        $this->setName('start-mailer-deamon')
            ->setDescription('start mailer deamon which send e-mails')
            ->addOption('debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $this->connection->getChannel();
        if($input->getOption('debug')){
            $this->listener->listen($channel, true);
        } else {
            $this->listener->listen($channel);
        }
    }
}