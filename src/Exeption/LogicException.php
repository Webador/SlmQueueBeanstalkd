<?php

namespace SlmQueueBeanstalkd\Exception;

use LogicException as BaseLogicException;

/**
 * Logic exception
 */
class LogicException extends BaseLogicException implements ExceptionInterface
{
}