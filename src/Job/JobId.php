<?php

namespace SlmQueueBeanstalkd\Job;

use Pheanstalk\Contract\JobIdInterface;

class JobId implements JobIdInterface  {
    /**
     * JobId constructor.
     * @param int $id
     */
    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @var int
     */
    protected $id;

}