<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Pheanstalk\Connection;
use Pheanstalk\Pheanstalk;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * PheanstalkFactory
 */
class PheanstalkFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Pheanstalk
    {
        $sm = $container->get('ServiceManager');
        $factory = new PheanstalkConnectionFactory();
        $connection = $factory($sm, \Pheanstalk::class);

//        var_dump($connection); die();

        $pheanstalk = new Pheanstalk($connection);

        return $pheanstalk;
    }
}
