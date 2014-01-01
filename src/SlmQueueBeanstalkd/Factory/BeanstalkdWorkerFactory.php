<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Worker\BeanstalkdWorker;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * BeanstalkdWorkerFactory
 */
class BeanstalkdWorkerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $workerOptions      = $serviceLocator->get('SlmQueue\Options\ModuleOptions')->getWorker();
        $queuePluginManager = $serviceLocator->get('SlmQueue\Queue\QueuePluginManager');

        return new BeanstalkdWorker($queuePluginManager, $workerOptions);
    }
}
