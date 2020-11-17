<?php

use Pheanstalk\Pheanstalk;
use PHPUnit\Framework\TestCase;
use SlmQueueBeanstalkd\Factory\PheanstalkFactory;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;

class PheanstalkFactoryTest extends TestCase
{
    public function testCanCreate()
    {
        $sm = ServiceManagerFactory::getServiceManager();
        $factory = new PheanstalkFactory();
        $service = $factory($sm, null);

        static::assertInstanceOf(Pheanstalk::class, $service);
    }
}