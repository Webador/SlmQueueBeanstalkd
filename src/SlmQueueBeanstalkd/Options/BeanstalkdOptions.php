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
}
