<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
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
        return $this($serviceLocator, BeanstalkdWorkerController::class);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (method_exists($container, 'getServiceLocator')) {
            $container = $container->getServiceLocator() ?: $container;
        }

        $worker  = $container->get('SlmQueueBeanstalkd\Worker\BeanstalkdWorker');
        $manager = $container->get('SlmQueue\Queue\QueuePluginManager');

        return new BeanstalkdWorkerController($worker, $manager);
    }
}
