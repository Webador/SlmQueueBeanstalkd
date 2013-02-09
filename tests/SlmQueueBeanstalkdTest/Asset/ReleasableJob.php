<?php

namespace SlmQueueBeanstalkdTest\Asset;

use SlmQueue\Job\AbstractJob;
use SlmQueueBeanstalkd\Job\Exception\ReleasableException;

class ReleasableJob extends AbstractJob
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        throw new ReleasableException();
    }
}
