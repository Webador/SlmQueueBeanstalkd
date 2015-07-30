<?php

namespace SlmQueueBeanstalkd\Options;

use Zend\Stdlib\AbstractOptions;

class QueueOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $tube = '';

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
