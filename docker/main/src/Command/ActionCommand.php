<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Amq\Client as AMQClient;


class ActionCommand extends Command {

        protected function configure(){

                $this->setName("action");

        }


        protected function execute(InputInterface $input , OutputInterface $output){


                $AMQClient = new AMQClient('client');

                foreach(['test', "test1", "TEZFAFZADA"] as $value){
                        $response = $AMQClient->call($value);
                        $output->write("message: ". $value. " response : ".$response);
                }

                $output->write($response);

        }

}
