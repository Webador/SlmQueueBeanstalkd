<?php

namespace SlmQueueBeanstalkd\Queue;

use Pheanstalk\Contract\JobIdInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use SlmQueueBeanstalkd\Exception\LogicException;
use SlmQueueBeanstalkd\Exception\RuntimeException;
use SlmQueueBeanstalkd\Exception\JobNotFoundException;
use DateInterval;
use DateTime;
use DateTimeZone;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;
use SlmQueueBeanstalkd\Job\JobId;
use SlmQueueBeanstalkd\Options\BeanstalkdQueueOptions;
use SlmQueueBeanstalkd\Options\JobOptions;
use SlmQueueDoctrine\Options\DoctrineOptions;

class BeanstalkdQueue extends AbstractQueue implements BeanstalkdQueueInterface
{

    public const STATUS_PENDING = 1;
    public const STATUS_RUNNING = 2;
    public const STATUS_DELETED = 3;
    public const STATUS_BURIED = 4;

    public const LIFETIME_DISABLED = 0;
    public const LIFETIME_UNLIMITED = -1;

    public const DEFAULT_PRIORITY = 1024;

    /**
     * @var Pheanstalk
     */
    protected $pheanstalk;

    /**
     * @var string
     */
    protected $tubeName;

    /**
     * @var BeanstalkdQueueOptions
     */
    protected $options = null;

    /**
     * Constructor
     *
     * @param Pheanstalk $pheanstalk
     * @param string $name
     * @param JobPluginManager $jobPluginManager
     * @param BeanstalkdQueueOptions|null $options
     */
    public function __construct(Pheanstalk $pheanstalk, $name, JobPluginManager $jobPluginManager, BeanstalkdQueueOptions $options = null) {
        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $name;
        if ($options !== null) {
            $this->options = clone $options;
            if ($options->getTube()) {
                $this->tubeName = $options->getTube();
            }
        }

        parent::__construct($name, $jobPluginManager);
    }


    /**
     * Get the name of the beanstalkd tube that is used for storing queue
     * @return string
     */
    public function getTubeName()
    {
        return $this->tubeName;
    }

    public function bury(JobInterface $job, array $options = []): void
    {
        $jobId = new JobId($job->getId());
        $jobOptions = new JobOptions($options);
        $this->pheanstalk->bury(
            $jobId,
            $jobOptions->getPriority()
        );
    }

    public function recover(int $executionTime): int
    {
        // TODO: Implement recover() method.
        throw new \Exception('Recovering is not supported yet.');
    }

    /**
     * {@inheritDoc}
     */
    public function kick($max)
    {
        $this->pheanstalk->useTube($this->getTubeName());

        return $this->pheanstalk->kick($max);
    }

    public function release(JobInterface $job, array $options = []): void
    {
        $jobId = new JobId($job->getId());
        $jobOptions = new JobOptions($options);
        $this->pheanstalk->release(
            $jobId,
            $jobOptions->getPriority(),
            $jobOptions->getDelay()
        );
    }

    /**
     * Get a job from the queue without processing it
     *
     * @param int $id Job identifier
     * @return JobInterface
     */
    public function peek(int $id): JobInterface
    {
        $jobId = new JobId($id);
        $info = $this->pheanstalk->peek($jobId);

        $job = $this->unserializeJob($info->getData());
        $job->setId($info->getId());

        return $job;
    }

    /**
     * Get a job from the queue without processing it
     *
     * @param JobInterface $job
     * @return JobInterface
     */
    public function peekJob(JobInterface $job): JobInterface
    {
        $jobId = new JobId($job->getId());
        $info = $this->pheanstalk->peek($jobId);

        return $this->unserializeJob($info->getData());
    }

    public function push(JobInterface $job, array $options = []): void
    {
        $jobOptions = new JobOptions($options);
        $theJob = $this->pheanstalk->put(
            $this->serializeJob($job),
            $jobOptions->getPriority(),
            $jobOptions->getDelay(),
            $jobOptions->getTTR()
        );

        $job->setId($theJob->getId());
    }

    public function pop(array $options = []): ?JobInterface
    {
        // @todo: test this
//        $jobOptions = new JobOptions($options);
        $job = $this->pheanstalk->reserve();
        if (!$job instanceof Job) {
            return null;
        }

        return $this->unserializeJob($job->getData(), array('__id__' => $job->getId()));
    }

    public function delete(JobInterface $job): void
    {
        $jobId = new JobId($job->getId());
        $this->pheanstalk->delete($jobId);
    }

    public function getOptions(): BeanstalkdQueueOptions
    {
        return $this->options;
    }

    public function getPheanstalk()
    {
        return $this->pheanstalk;
    }
}