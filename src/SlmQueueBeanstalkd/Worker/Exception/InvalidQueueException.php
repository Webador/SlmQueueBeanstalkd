<?php

namespace SlmQueueBeanstalkd\Worker\Exception;

use SlmQueue\Exception\ExceptionInterface;
use InvalidArgumentException;

class InvalidQueueException extends InvalidArgumentException implements ExceptionInterface
{

}