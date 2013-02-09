<?php

namespace SlmQueueBeanstalkd\Factory;

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

        return new Tube($pheanstalk, $requestedName, $jobPluginManager);
    }
}
