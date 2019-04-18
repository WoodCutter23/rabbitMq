<?php


namespace App\Workers;

use App\Helpers\RabbitMqConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Helpers\Enum;
use App\Controllers\SendController;
use PhpAmqpLib\Wire\AMQPTable;
use Faker\Factory;


class RabbitWorker
{
    protected $channel;
    protected $rabbitWorker;

    public function __construct(RabbitMqConnection $rabbitMqConnection)
    {
            $this->channel = $rabbitMqConnection->getChannel();
    }

    public function queueMessage($queue)
    {

        if($queue == SendController::ALFA_QUEUE) {
            $type = 'alfa';
            $priority =10;
        }

        if($queue == SendController::OMEGA_QUEUE){
            $type = 'omega';
            $priority = 1;
        }

        $messages = $this->getMessages($type);

        foreach ($messages as $message) {
            $this->execute(SendController::QUEUE, $message, $priority);
        }

    }

    protected function execute($queue, $message, $priority = 1)
    {
        $args = new AMQPTable();
        $args->set('x-max-priority', 10);
        $this->channel->queue_declare(
            $queue,
            false,
            true,
            false,
            false,
            false,
            $args
        );

        $msg = new AMQPMessage($message, [
            'delivery_mode' => 2,
            'priority' => $priority,
        ]);

        $this->channel->basic_publish(
            $msg,       	#message
            '',         	#exchange
            $queue 	#routing key
        );
    }

    protected function getMessages($type = 'omega') :array
    {
        $messages = [];

        if($type == 'omega') {
            for ($i = 0; $i < 200; $i++) {
                $messages[] = 'Usualy header';
            }

            return $messages;
        }

        if($type == 'alfa') {
            $messages[] = 'SUPER ALFA HEDAER';
            return $messages;
        }
    }
}