<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Options\BeanstalkdQueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * TubeFactory
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

        // Check if the user has specified options for the given queues, or create a default one
        $queuesOptions = $parentLocator->get('SlmQueue\Options\ModuleOptions')->getQueues();

        if (isset($queuesOptions[$requestedName])) {
            $options = new BeanstalkdQueueOptions($queuesOptions[$requestedName]);
        } else {
            $options = new BeanstalkdQueueOptions();
        }

        return new BeanstalkdQueue($pheanstalk, $options, $requestedName, $jobPluginManager);
    }
}
