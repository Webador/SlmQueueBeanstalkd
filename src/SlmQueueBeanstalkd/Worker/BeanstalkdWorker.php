<?php

namespace SlmQueueBeanstalkd\Worker;

use Exception;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueue\Worker\Event\ProcessJobEvent;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface;

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
            return ProcessJobEvent::JOB_STATUS_UNKNOWN;
        }

        /**
         * In Beanstalkd, if an error occurs (exception for instance), the job
         * is automatically reinserted into the queue after a configured delay
         * (the "visibility_timeout" option). If the job executed correctly, it
         * must explicitly be removed
         */
        try {
            $job->execute();
            $queue->delete($job);

            return ProcessJobEvent::JOB_STATUS_SUCCESS;
        } catch (Exception $exception) {
            // Do nothing, the job will be reinserted automatically for another try
            return ProcessJobEvent::JOB_STATUS_FAILURE_RECOVERABLE;
        }
    }
}
