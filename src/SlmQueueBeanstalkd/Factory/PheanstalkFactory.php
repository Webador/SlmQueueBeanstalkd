<?php

namespace SlmQueueBeanstalkd\Factory;

use Pheanstalk_Pheanstalk as Pheanstalk;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * PheanstalkFactory
 */
class PheanstalkFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = '', $requestedName = '')
    {
        /** @var $beanstalkdOptions \SlmQueueBeanstalkd\Options\BeanstalkdOptions */
        $beanstalkdOptions = $serviceLocator->get('SlmQueueBeanstalkd\Options\BeanstalkdOptions');
        $connectionOptions = $beanstalkdOptions->getConnection();

        return new Pheanstalk(
            $connectionOptions->getHost(),
            $connectionOptions->getPort(),
            $connectionOptions->getTimeout()
        );
    }
}
