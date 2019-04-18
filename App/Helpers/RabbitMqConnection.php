<?php

namespace App\Helpers;

use PhpAmqpLib\Connection\AMQPConnection;

class RabbitMqConnection

{
    protected $connection;
    protected  $channel;

    public function __construct()
    {
        $config = require(__DIR__.'/../Config/config.php');

        $this->connection = new AMQPConnection($config->rabbitMq->host, $config->rabbitMq->port, $config->rabbitMq->username, $config->rabbitMq->password);
        $this->channel = $this->connection->channel();

    }

    public function getChannel()
    {
        return $this->channel;
    }

    public  function getConnection()
    {
        return $this->connection;
    }
}