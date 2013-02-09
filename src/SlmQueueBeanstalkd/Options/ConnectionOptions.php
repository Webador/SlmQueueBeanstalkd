<?php

namespace SlmQueueBeanstalkd\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * ConnectionOptions
 */
class ConnectionOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var integer
     */
    protected $timeout;


    /**
     * Set the connection host
     *
     * @param  string $host
     * @return void
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Get the connection host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the connection port
     *
     * @param  int $port
     * @return void
     */
    public function setPort($port)
    {
        $this->port = (int) $port;
    }

    /**
     * Get the connection port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the connection timeout
     *
     * @param  int $timeout
     * @return void
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;
    }

    /**
     * Get the connection timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}
