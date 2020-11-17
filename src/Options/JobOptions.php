<?php

namespace SlmQueueBeanstalkd\Options;

use Laminas\Stdlib\AbstractOptions;
use Pheanstalk\Pheanstalk;

class JobOptions extends AbstractOptions {
    protected $__strictMode__ = false;

    public function getOption($name, $default = null) {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $default;
    }

    public function getPriority($default = Pheanstalk::DEFAULT_PRIORITY)
    {
        return $this->getOption('priority', $default);
    }

    public function getDelay($default = Pheanstalk::DEFAULT_DELAY)
    {
        return $this->getOption('delay', $default);
    }

    public function getTTR($default = Pheanstalk::DEFAULT_TTR)
    {
        return $this->getOption('ttr', $default);
    }
}