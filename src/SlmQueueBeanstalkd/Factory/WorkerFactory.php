<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Worker\Worker as BeanstalkdWorker;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * WorkerFactory
 */
class WorkerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $workerOptions      = $serviceLocator->get('SlmQueue\Options\WorkerOptions');
        $queuePluginManager = $serviceLocator->get('SlmQueue\Queue\QueuePluginManager');

        return new BeanstalkdWorker($queuePluginManager, $workerOptions);
    }
}
