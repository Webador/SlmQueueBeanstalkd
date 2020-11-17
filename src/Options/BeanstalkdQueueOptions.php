<?php

namespace SlmQueueBeanstalkd\Options;

use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use Laminas\Stdlib\AbstractOptions;

/**
 * BeanstalkdQueueOptions
 */
class BeanstalkdQueueOptions extends AbstractOptions
{
    /**
     * how long to keep deleted (successful) jobs (in minutes)
     *
     * @var int
     */
    protected $deletedLifetime = BeanstalkdQueue::LIFETIME_DISABLED;

    /**
     * how long to keep buried (failed) jobs (in minutes)
     *
     * @var int
     */
    protected $buriedLifetime = BeanstalkdQueue::LIFETIME_DISABLED;

    /**
     * @var string
     */
    protected $tube = '';


    /**
     * @return int
     */
    public function getDeletedLifetime(): int
    {
        return $this->deletedLifetime;
    }

    /**
     * @param int $deletedLifetime
     */
    public function setDeletedLifetime(int $deletedLifetime): void
    {
        $this->deletedLifetime = $deletedLifetime;
    }

    /**
     * @return int
     */
    public function getBuriedLifetime(): int
    {
        return $this->buriedLifetime;
    }

    /**
     * @param int $buriedLifetime
     */
    public function setBuriedLifetime(int $buriedLifetime): void
    {
        $this->buriedLifetime = $buriedLifetime;
    }

    /**
     * Get beanstalkd tube name for queue
     * @return string
     */
    public function getTube()
    {
        return $this->tube;
    }

    /**
     * Set beanstalkd tube for queue
     * @param string $tube
     */
    public function setTube($tube)
    {
        $this->tube = $tube;
    }
}