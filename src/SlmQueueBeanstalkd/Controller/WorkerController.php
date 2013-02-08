<?php

namespace SlmQueueBeanstalkd\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * This controller allow to execute jobs using the command line
 */
class WorkerController extends AbstractActionController
{
    /**
     * Process the queue given in parameter
     */
    public function processAction()
    {
        /** @var $worker \SlmQueueBeanstalkd\Worker\Worker */
        $worker    = $this->serviceLocator->get('SlmQueueBeanstalkd\Worker\Worker');
        $queueName = $this->params('queue');

        $count = $worker->processQueue($queueName);

        return sprintf(
            "\nWork for queue %s is done, %s jobs were processed\n",
            $queueName,
            $count
        );
    }
}
