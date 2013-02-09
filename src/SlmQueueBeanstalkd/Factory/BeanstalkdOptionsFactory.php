<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Options\BeanstalkdOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BeanstalkdOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new BeanstalkdOptions($serviceLocator->get('Config')['slm_queue']['beanstalkd']);
    }
}
