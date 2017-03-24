<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueBeanstalkd\Options\QueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * BeanstalkdQueueFactory
 */
class BeanstalkdQueueFactory implements FactoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, BeanstalkdQueue::class);
    }


    /**
     * Returns custom beanstalkd options for specified queue
     *
     * @param ContainerInterface $serviceLocator
     * @param string $queueName
     *
     * @return QueueOptions
     */
    protected function getQueueOptions(ContainerInterface $serviceLocator, $queueName)
    {
        $config = $serviceLocator->get('Config');
        $queuesOptions = isset($config['slm_queue']['queues']) ? $config['slm_queue']['queues'] : [];
        $queueOptions = isset($queuesOptions[$queueName]) ? $queuesOptions[$queueName] : [];

        return new QueueOptions($queueOptions);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (method_exists($container, 'getServiceLocator')) {
            $container = $container->getServiceLocator() ?: $container;
        }

        $pheanstalk       = $container->get('SlmQueueBeanstalkd\Service\PheanstalkService');
        $jobPluginManager = $container->get('SlmQueue\Job\JobPluginManager');

        $queueOptions = $this->getQueueOptions($container, $requestedName);

        return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
    }
}
