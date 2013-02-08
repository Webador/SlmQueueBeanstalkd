<?php

namespace SlmQueueBeanstalkd\Service;

use Pheanstalk_Pheanstalk as Pheanstalk;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;

/**
 * PheanstalkService is a thin wrapper around Pheanstalk
 */
class PheanstalkService
{
    /**
     * @var Pheanstalk
     */
    protected $pheanstalk;

    /**
     * @var BeanstalkdOptions
     */
    protected $options;


    /**
     * Constructor
     *
     * @param Pheanstalk        $pheanstalk
     * @param BeanstalkdOptions $options
     */
    public function __construct(Pheanstalk $pheanstalk, BeanstalkdOptions $options)
    {
        $this->pheanstalk = $pheanstalk;
        $this->options    = $options;
    }

    /**
     * Get Pheanstalk object
     *
     * @return Pheanstalk
     */
    public function getPheanstalk()
    {
        return $this->pheanstalk;
    }

    /**
     * @return BeanstalkdOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
