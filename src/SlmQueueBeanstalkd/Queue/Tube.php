<?php

namespace SlmQueueBeanstalkd\Queue;

use Pheanstalk_Job;
use Pheanstalk_Pheanstalk as Pheanstalk;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;
use SlmQueueBeanstalkd\Options\TubeOptions;

/**
 * BeanstalkdQueue
 */
class Tube extends AbstractQueue implements TubeInterface
{
    /**
     * @var Pheanstalk
     */
    protected $pheanstalk;

    /**
     * @var TubeOptions
     */
    protected $tubeOptions;

    /**
     * Constructor
     *
     * @param Pheanstalk       $pheanstalk
     * @param TubeOptions      $options
     * @param string           $name
     * @param JobPluginManager $jobPluginManager
     */
    public function __construct(Pheanstalk $pheanstalk, TubeOptions $options, $name, JobPluginManager $jobPluginManager)
    {
        $this->pheanstalk  = $pheanstalk;
        $this->tubeOptions = $options;

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
            $this->name,
            $job->jsonSerialize(),
            isset($options['priority']) ? $options['priority'] : $this->tubeOptions->getPriority(),
            isset($options['delay']) ? $options['delay'] : $this->tubeOptions->getDelay(),
            isset($options['ttr']) ? $options['ttr'] : $this->tubeOptions->getTtr()
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
            $this->name,
            isset($options['timeout']) ? $options['timeout'] : null
        );

        if (!$job instanceof Pheanstalk_Job) {
            return null;
        }

        $data = json_decode($job->getData(), true);

        return $this->createJob($data['class'], $data['content'], array('id' => $job->getId()));
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
            isset($options['priority']) ? $options['priority'] : $this->tubeOptions->getPriority(),
            isset($options['delay']) ? $options['delay'] : $this->tubeOptions->getDelay()
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
            isset($options['priority']) ? $options['priority'] : $this->tubeOptions->getPriority()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function kick($max)
    {
        $this->pheanstalk->useTube($this->name);

        return $this->pheanstalk->kick($max);
    }
}
