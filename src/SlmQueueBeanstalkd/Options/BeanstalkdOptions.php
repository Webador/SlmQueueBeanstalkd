<?php

namespace SlmQueueBeanstalkd\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * BeanstalkdOptions
 */
class BeanstalkdOptions extends AbstractOptions
{
    /**
     * @var ConnectionOptions
     */
    protected $connection;

    /**
     * @var array
     */
    protected $tubes = [];

    /**
     * Set the connection options
     *
     * @param array $options
     */
    public function setConnection(array $options)
    {
        $this->connection = new ConnectionOptions($options);
    }

    /**
     * Get the connection options
     *
     * @return ConnectionOptions
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set beanstalkd tube names to use for queues
     * @param array $tubes
     */
    public function setTubes(array $tubes)
    {
        $this->tubes = $tubes;
    }

    /**
     * Get beanstalkd tube names to use for queues
     * @return array
     */
    public function getTubes()
    {
        return $this->tubes;
    }
}
