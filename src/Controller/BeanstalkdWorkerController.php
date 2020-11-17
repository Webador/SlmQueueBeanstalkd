<?php

namespace SlmQueueBeanstalkd\Controller;

use SlmQueue\Controller\AbstractWorkerController;
use SlmQueue\Controller\Exception\WorkerProcessException;
use SlmQueue\Exception\ExceptionInterface;
use SlmQueue\Queue\QueuePluginManager;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface;

/**
 * Worker controller
 */
class BeanstalkdWorkerController extends AbstractWorkerController
{
    /**
     * Recover long running jobs
     */
    public function recoverAction(): string
    {
        $queueName     = $this->params('queue');
        $executionTime = $this->params('executionTime', 0);
        $queue         = $this->queuePluginManager->get($queueName);

        if (! $queue instanceof BeanstalkdQueueInterface) {
            return sprintf("\nQueue % does not support the recovering of job\n\n", $queueName);
        }

        try {
            $count = $queue->recover($executionTime);
        } catch (ExceptionInterface $exception) {
            throw new WorkerProcessException("An error occurred", $exception->getCode(), $exception);
        }

        return sprintf(
            "\nWork for queue %s is done, %s jobs were recovered\n\n",
            $queueName,
            $count
        );
    }

    public function processAction(): string
    {
        $options = $this->params()->fromRoute();
        $name = $options['queue'];
        $queue = $this->queuePluginManager->get($name);

        try {
            $messages = $this->worker->processQueue($queue, $options);
        } catch (ExceptionInterface $e) {
            throw new WorkerProcessException(
                'Caught exception while processing queue',
                $e->getCode(),
                $e
            );
        }

        return $this->formatOutput($name, $messages);
    }

    public function statsAction() {
        // @todo
        $name = 'mailing';
        $worker = $this->worker;
        $queue = $this->queuePluginManager->get($name);

        $stats = $queue->getPheanstalk()->stats();

        var_dump($stats);
        die();
    }
}