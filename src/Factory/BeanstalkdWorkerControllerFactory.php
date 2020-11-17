<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueue\Queue\QueuePluginManager;
use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use SlmQueueBeanstalkd\Worker\BeanstalkdWorker;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

/**
 * WorkerFactory
 */
class BeanstalkdWorkerControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BeanstalkdWorkerController {
        $sm = $container->get('ServiceManager');

        $worker             = $sm->get(BeanstalkdWorker::class);
        $queuePluginManager = $sm->get(QueuePluginManager::class);

        return new BeanstalkdWorkerController($worker, $queuePluginManager);
    }
}