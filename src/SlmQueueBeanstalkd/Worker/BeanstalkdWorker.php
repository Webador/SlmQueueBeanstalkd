<?php

namespace SlmQueueBeanstalkd\Worker;

use Exception;
use Pheanstalk_Pheanstalk as Pheanstalk;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface;
use SlmQueueBeanstalkd\Worker\Exception\InvalidQueueException;

/**
 * Worker for Beanstalkd
 */
class BeanstalkdWorker extends AbstractWorker
{
    /**
     * {@inheritDoc}
     */
    public function processJob(JobInterface $job, QueueInterface $queue)
    {
        if (!$queue instanceof BeanstalkdQueueInterface) {
            throw new InvalidQueueException(sprintf(
                'Invalid queue type given, expected a SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface, %s given',
                get_class($queue)
            ));
        }

        try {
            $job->execute();
            $queue->delete($job);
        } catch (Exception $exception) {
            $queue->bury($job, array('priority' => Pheanstalk::DEFAULT_PRIORITY));
        }
    }
}
