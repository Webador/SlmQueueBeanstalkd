<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
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
        return $this($serviceLocator, BeanstalkdOptions::class);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        return new BeanstalkdOptions($config['slm_queue']['beanstalkd']);
    }
}
