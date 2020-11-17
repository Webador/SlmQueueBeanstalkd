<?php

namespace SlmQueueBeanstalkdTest\Asset;

use Pheanstalk\Contract\JobIdInterface;
use SlmQueue\Job\AbstractJob;
use SlmQueueBeanstalkd\Exception\JobNotFoundException;

class SimplePheanstalkJob extends AbstractJob implements JobIdInterface
{
    public function execute(): ?int
    {
        // Just set some stupid metadata
        $this->setMetadata('foo', 'bar');

        return 999;
    }

    public function getId(): int
    {
        $id = parent::getId();
        if (is_int($id)) {
            return $id;
        }

        throw new \Exception('Unable to get job id.');
    }
}
