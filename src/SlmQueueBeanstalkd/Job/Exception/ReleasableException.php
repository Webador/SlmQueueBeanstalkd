<?php

namespace SlmQueueBeanstalkd\Job\Exception;

use RuntimeException;

/**
 * ReleasableException. Throw this exception in the "execute" method of your job so that the worker
 * puts back the job into the queue
 */
class ReleasableException extends RuntimeException
{
    /**
     * @var array
     */
    protected $options;


    /**
     * Valid options are:
     *      - priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
     *      - delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)
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
