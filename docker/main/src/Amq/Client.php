<?php

namespace App\Amq;


use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Client {

        private $connection;
        private $channel;
        private $callback;
        private $correlationId;
        private $response;
        private $replyTo;

        public function __construct($replyTo){
                $this->replyTo = $replyTo;

                $this->connection = new AMQPConnection(
                        'rabbitmq', 5672, 'guest', 'guest'
                );
                $this->channel = $this->connection->channel();

                list ($this->callback, , ) = $this->channel->queue_declare("", false, false, true, false);

                $this->channel->basic_consume(
                        $this->callback, '', false, false, false, false,
                        [ $this, 'onResponse' ]
                );
        }

        public function onResponse($response){
                if($response->get('correlation_id') == $this->correlationId){
                        $this->response = $response->body;
                }

        }

        public function call($message){

                $this->response = null;
                $this->correlationId = uniqid();

                $msg = new AMQPMessage(
                        (string) $message,
                        [
                                'correlation_id'=> $this->correlationId,
                                'reply_to'=> $this->replyTo
                        ]);

                $this->channel->basic_publish($msg, '', 'rpc_queue');

                while(!$this->response){
                        $this->channel->wait();

                }

                return $this->response;
        }


}

