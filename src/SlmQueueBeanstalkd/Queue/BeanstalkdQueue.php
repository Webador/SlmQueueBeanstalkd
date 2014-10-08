<?php

namespace SlmQueueBeanstalkd\Queue;

use Pheanstalk_Job;
use Pheanstalk_Pheanstalk as Pheanstalk;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;

/**
 * BeanstalkdQueue
 */
class BeanstalkdQueue extends AbstractQueue implements BeanstalkdQueueInterface
{
    /**
     * @var Pheanstalk
     */
    protected $pheanstalk;


    /**
     * Constructor
     *
     * @param Pheanstalk       $pheanstalk
     * @param string           $name
     * @param JobPluginManager $jobPluginManager
     */
    public function __construct(Pheanstalk $pheanstalk, $name, JobPluginManager $jobPluginManager)
    {
        $this->pheanstalk = $pheanstalk;
        parent::__construct($name, $jobPluginManager);
    }

    /**
     * Valid options are:
     *      - priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
     *      - delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)
     *      - ttr: in seconds, how much time a job can be reserved for (default to 60)
     *
     * {@inheritDoc}
     */
    public function push(JobInterface $job, array $options = array())
    {
        $identifier = $this->pheanstalk->putInTube(
            $this->getName(),
            $this->serializeJob($job),
            isset($options['priority']) ? $options['priority'] : Pheanstalk::DEFAULT_PRIORITY,
            isset($options['delay']) ? $options['delay'] : Pheanstalk::DEFAULT_DELAY,
            isset($options['ttr']) ? $options['ttr'] : Pheanstalk::DEFAULT_TTR
        );

        $job->setId($identifier);
    }

    /**
     * Valid option is:
     *      - timeout: by default, when we ask for a job, it will block until a job is found (possibly forever if
     *                 new jobs never come). If you set a timeout (in seconds), it will return after the timeout is
     *                 expired, even if no jobs were found
     *
     * {@inheritDoc}
     */
    public function pop(array $options = array())
    {
        $job = $this->pheanstalk->reserveFromTube(
            $this->getName(),
            isset($options['timeout']) ? $options['timeout'] : null
        );

        if (!$job instanceof Pheanstalk_Job) {
            return null;
        }

        return $this->unserializeJob($job->getData(), array('id' => $job->getId()));
    }

    /**
     * {@inheritDoc}
     */
    public function delete(JobInterface $job)
    {
        $this->pheanstalk->delete($job);
    }

    /**
     * Valid options are:
     *      - priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
     *      - delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)
     *
     * {@inheritDoc}
     */
    public function release(JobInterface $job, array $options = array())
    {
        $this->pheanstalk->release(
            $job,
            isset($options['priority']) ? $options['priority'] : Pheanstalk::DEFAULT_PRIORITY,
            isset($options['delay']) ? $options['delay'] : Pheanstalk::DEFAULT_DELAY
        );
    }

    /**
     * Valid option is:
     *      - priority: the lower the priority is, the sooner the job get kicked
     *
     * {@inheritDoc}
     */
    public function bury(JobInterface $job, array $options = array())
    {
        $this->pheanstalk->bury(
            $job,
            isset($options['priority']) ? $options['priority'] : Pheanstalk::DEFAULT_PRIORITY
        );
    }

    /**
     * {@inheritDoc}
     */
    public function kick($max)
    {
        $this->pheanstalk->useTube($this->getName());
        return $this->pheanstalk->kick($max);
    }
}
