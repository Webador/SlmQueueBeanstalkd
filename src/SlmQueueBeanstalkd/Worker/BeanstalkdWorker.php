<?php

namespace SlmQueueBeanstalkd\Worker;

use Exception;
use Pheanstalk_Pheanstalk as Pheanstalk;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueueBeanstalkd\Job\Exception as JobException;
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
            return;
        }

        try {
            $job->execute();
            $queue->delete($job);
        } catch(JobException\ReleasableException $exception) {
            $queue->release($job, $exception->getOptions());
        } catch (JobException\BuryableException $exception) {
            $queue->bury($job, $exception->getOptions());
        } catch (Exception $exception) {
            $queue->bury($job, array('priority' => Pheanstalk::DEFAULT_PRIORITY));
        }
    }
}
