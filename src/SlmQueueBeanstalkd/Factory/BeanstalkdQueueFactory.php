<?php

namespace SlmQueueBeanstalkd\Factory;

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
    public function createService(ServiceLocatorInterface $serviceLocator, $name = '', $requestedName = '')
    {
        $parentLocator    = $serviceLocator->getServiceLocator();
        $pheanstalk       = $parentLocator->get('SlmQueueBeanstalkd\Service\PheanstalkService');
        $jobPluginManager = $parentLocator->get('SlmQueue\Job\JobPluginManager');

        $queueOptions = $this->getQueueOptions($parentLocator, $requestedName);

        return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
    }

    /**
     * Returns custom beanstalkd options for specified queue
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $queueName
     * @return QueueOptions
     */
    protected function getQueueOptions(ServiceLocatorInterface $serviceLocator, $queueName)
    {
        $config = $serviceLocator->get('Config');
        $queuesOptions = isset($config['slm_queue']['queues'])? $config['slm_queue']['queues'] : array();
        $queueOptions = isset($queuesOptions[$queueName])? $queuesOptions[$queueName] : array();

        return new QueueOptions($queueOptions);
    }
}
