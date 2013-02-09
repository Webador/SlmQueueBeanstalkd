<?php

namespace SlmQueueBeanstalkd\Job\Exception;

use RuntimeException;

/**
 * BuryableException
 */
class BuryableException extends RuntimeException
{
    /**
     * @var array
     */
    protected $options;


    /**
     * Valid option is:
     *      - priority: the lower the priority is, the sooner the job get kicked
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Get the options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
