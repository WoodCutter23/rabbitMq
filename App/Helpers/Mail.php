<?php

namespace App\Helpers;

use Faker\Factory;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class Mail
{
    protected $transport;
    protected $mailer;
    protected $message;

    public function __construct()
    {
        $config = require(__DIR__.'/../Config/config.php');

        $this->transport = (new Swift_SmtpTransport($config->mailTrap->host, $config->mailTrap->port))
            ->setUsername($config->mailTrap->username)
            ->setPassword(($config->mailTrap->password));
        $this->mailer = new Swift_Mailer($this->transport);

        $this->message = new Swift_Message();
    }

    public function send($header)
    {
        $this->message->setSubject($header);
        $this->message->setFrom(['danya@gmail.com' => 'Danya']);
        $this->message->addTo('recipient@gmail.com','receiver name');

        $body = $this->getText();

        $this->message->setBody($body);
        sleep(5);
        $this->mailer->send($this->message);
    }

    protected function getText()
    {
        $faker = Factory::create();
        return $faker->text;
    }
}
