<?php

namespace SlmQueueBeanstalkd\Worker;

use Exception;
use Pheanstalk_Pheanstalk as Pheanstalk;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueueBeanstalkd\Queue\TubeInterface;

/**
 * Worker for Beanstalkd
 */
class Worker extends AbstractWorker
{
    /**
     * {@inheritDoc}
     */
    public function processJob(JobInterface $job, QueueInterface $queue)
    {
        if (!$queue instanceof TubeInterface) {
            throw new InvalidQueueException(sprintf(
                'Invalid queue type given, expected a SlmQueueBeanstalkd\Queue\TubeInterface, %s given',
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
