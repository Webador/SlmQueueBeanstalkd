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
        $jobPluginManager = $serviceLocator->get('SlmQueue\Job\JobPluginManager');
        $pheanstalk       = $serviceLocator->get('SlmQueueBeanstalkd\Service\PheanstalkService');

        return new Tube($pheanstalk, $requestedName, $jobPluginManager);
    }
}
