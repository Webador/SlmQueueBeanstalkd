<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Controller\WorkerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * WorkerFactory
 */
class WorkerControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $worker = $serviceLocator->getServiceLocator()
                                 ->get('SlmQueueBeanstalkd\Worker\Worker');

        return new WorkerController($worker);
    }
}
