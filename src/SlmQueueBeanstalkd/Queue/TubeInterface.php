<?php

namespace SlmQueueBeanstalkd\Queue;

use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;

/**
 * Contract for a Beanstalkd queue, aka a tube
 */
interface TubeInterface extends QueueInterface
{
    /**
     * Put a job that was popped back to the queue
     *
     * @param  JobInterface $job
     * @param  array $options
     * @return mixed
     */
    public function release(JobInterface $job, array $options = array());

    /**
     * Bury a job. When a job is buried, it won't be retrieved from the queue, unless the job is kicked
     *
     * @param  JobInterface $job
     * @param  array        $options
     * @return void
     */
    public function bury(JobInterface $job, array $options = array());

    /**
     * Kick a specified number of buried jobs, hence making them "ready" again
     *
     * @param  int $max The maximum jobs to kick
     * @return int Number of jobs kicked
     */
    public function kick($max);
}
