<?php

namespace App\Controllers;

use Faker\Provider\hy_AM\Company;
use Rakit\Validation\Validator;
use App\Helpers\RabbitMqConnection;
use Exception;
use App\Workers\RabbitWorker;
use Faker\Factory;

class SendController
{
    protected  $channel;
    /**
     * @var RabbitMqConnection
     */
    private $rabbitMqConnection;
    private $rabbitworker;

    public function __construct(RabbitMqConnection $rabbitMqConnection, RabbitWorker $rabbitWorker)
    {

        $this->rabbitMqConnection = $rabbitMqConnection;
        $this->rabbitworker = $rabbitWorker;
    }

    const QUEUE = 'queue';
    const ALFA = 0;
    const ALFA_QUEUE = 'alfa';
    const OMEGA_QUEUE = 'omega';
    const OMEGA = 1;
    public function send()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);

        if(!$_POST) {
            throw  new  Exception('Запрос не должен быть пустым');
        }

        $validator = new Validator;

        $validation = $validator->make($_POST + $_FILES, [
            'type' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            $errors = $errors->toArray();
            throw  new  Exception(json_encode($errors));
        }

        if($_POST['type'] == self::ALFA) {
            $this->rabbitworker->queueMessage(self::ALFA_QUEUE);
        }

        if($_POST['type'] == self::OMEGA) {
            $this->rabbitworker->queueMessage(self::OMEGA_QUEUE);
        }
    }

}