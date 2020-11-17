<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueue\Job\JobPluginManager;
use SlmQueueBeanstalkd\Options\BeanstalkdQueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * BeanstalkdQueueFactory
 */
class BeanstalkdQueueFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BeanstalkdQueue
    {
        /** @var \Laminas\ServiceManager\ServiceManager $sm */
        $sm = $container->get('ServiceManager');

        $config        = $container->get('config');
        $queuesConfig = $config['slm_queue']['queues'];
        $options     = isset($queuesConfig[$requestedName]) ? $queuesConfig[$requestedName] : [];
        $queueOptions  = new BeanstalkdQueueOptions($options);

        $jobPluginManager = $container->get(JobPluginManager::class);
        $pheanstalk = $sm->get(\Pheanstalk::class);


        return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
    }
}