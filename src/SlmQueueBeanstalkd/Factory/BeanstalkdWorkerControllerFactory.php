<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * BeanstalkdWorkerControllerFactory
 */
class BeanstalkdWorkerControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $worker = $serviceLocator->getServiceLocator()
                                 ->get('SlmQueueBeanstalkd\Worker\BeanstalkdWorker');

        return new BeanstalkdWorkerController($worker);
    }
}
