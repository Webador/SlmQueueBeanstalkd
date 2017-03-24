<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Pheanstalk\Pheanstalk;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;
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
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Pheanstalk::class);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $beanstalkdOptions \SlmQueueBeanstalkd\Options\BeanstalkdOptions */
        $beanstalkdOptions = $container->get(BeanstalkdOptions::class);
        $connectionOptions = $beanstalkdOptions->getConnection();

        return new Pheanstalk(
            $connectionOptions->getHost(),
            $connectionOptions->getPort(),
            $connectionOptions->getTimeout()
        );
    }
}
