<?php

namespace SlmQueueBeanstalkd\Worker\Exception;

use SlmQueueBeanstalkd\Exception\ExceptionInterface;
use InvalidArgumentException;

class InvalidQueueException extends InvalidArgumentException implements ExceptionInterface
{

}
