<?php

namespace SlmQueueBeanstalkd\Job;

use Pheanstalk\Contract\JobIdInterface;
use SlmQueue\Job\JobInterface as SlmJobInterface;

interface JobInterface extends JobIdInterface, SlmJobInterface
{

}