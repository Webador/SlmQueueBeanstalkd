<?php

namespace SlmQueueBeanstalkd\Worker;

use Exception;
use Pheanstalk_Pheanstalk as Pheanstalk;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueueBeanstalkd\Job\Exception as JobException;
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
            error_log('BeanstalkdWorker->processJob() unexpected exception:', 0);
            error_log($exception->getMessage(), 0);
            error_log($exception->getPrevious(), 0);            
            $queue->bury($job, array('priority' => Pheanstalk::DEFAULT_PRIORITY));
        }
    }
}
