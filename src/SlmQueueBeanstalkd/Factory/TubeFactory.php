<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Options\TubeOptions;
use SlmQueueBeanstalkd\Queue\Tube;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * TubeFactory
 */
class TubeFactory implements FactoryInterface
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
            $options = new TubeOptions($queuesOptions[$requestedName]);
        } else {
            $options = new TubeOptions();
        }

        return new Tube($pheanstalk, $options, $requestedName, $jobPluginManager);
    }
}
