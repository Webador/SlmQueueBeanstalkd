<?php

namespace SlmQueueBeanstalkdTest\Asset;

use Exception;
use SlmQueue\Job\AbstractJob;

class ExceptionJob extends AbstractJob
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        throw new Exception();
    }
}
