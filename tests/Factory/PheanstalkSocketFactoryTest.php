<?php

use Pheanstalk\Connection;
use PHPUnit\Framework\TestCase;
use SlmQueueBeanstalkd\Factory\PheanstalkConnectionFactory;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;

class PheanstalkSocketFactoryTest extends TestCase
{
    public function testCanCreate()
    {
        $sm = ServiceManagerFactory::getServiceManager();
        $factory = new PheanstalkConnectionFactory();

        $service = $factory($sm, null);

        static::assertInstanceOf(Connection::class, $service);
    }
}