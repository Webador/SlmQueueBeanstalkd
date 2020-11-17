<?php

namespace SlmQueueBeanstalkdTest\Factory;

use PHPUnit\Framework\TestCase;
use SlmQueueBeanstalkd\Factory\BeanstalkdQueueFactory;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;

class BeanstalkdQueueFactoryTest extends TestCase
{
    public function testCreateServiceGetsInstance(): void
    {
        $sm = ServiceManagerFactory::getServiceManager();
        $factory = new BeanstalkdQueueFactory();
        $service = $factory($sm, null);

        static::assertInstanceOf(BeanstalkdQueue::class, $service);
    }

    public function testSpecifiedQueueOptionsOverrideModuleDefaults(): void
    {
        $sm = ServiceManagerFactory::getServiceManager();
        $config = $sm->get('config');

        $factory = new BeanstalkdQueueFactory();
        $service = $factory($sm, 'my-beanstalkd-queue');

        static::assertEquals(
            $service->getOptions()->getDeletedLifetime(),
            $config['slm_queue']['queues']['my-beanstalkd-queue']['deleted_lifetime']
        );
        static::assertEquals(
            $service->getOptions()->getBuriedLifetime(),
            $config['slm_queue']['queues']['my-beanstalkd-queue']['buried_lifetime']
        );
    }
}