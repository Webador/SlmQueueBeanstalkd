<?php

namespace SlmQueueBeanstalkdTest\Asset;

use SlmQueue\Job\AbstractJob;
use SlmQueueBeanstalkd\Job\Exception\BuryableException;

class BuryableJob extends AbstractJob
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        throw new BuryableException();
    }
}
