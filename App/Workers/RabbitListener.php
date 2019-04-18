<?php
namespace App\Workers;

use App\Helpers\Mail;
use App\Controllers\SendController;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitListener
{

    protected $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }


    public function listen($channel , $debug = false)
    {
        $args = new AMQPTable();
        $args->set('x-max-priority', 10);

        $channel->queue_declare(SendController::QUEUE, false, true, false,false, false, $args);


            while (true) {

                $msg = $channel->basic_get(
                    SendController::QUEUE,
                    true,
                    null
                )->body;

                if($msg) {
                    $this->processOrder($msg, $debug);
                }
            }
    }

    public function processOrder($msg , $debug)
    {
        if($debug) {
            sleep(5);
            echo "$msg \n";
        } else {
            $this->mail->send($msg);
        }
    }
}
