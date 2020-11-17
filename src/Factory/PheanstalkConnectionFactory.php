<?php

namespace SlmQueueBeanstalkd\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Pheanstalk\Connection;
use Pheanstalk\SocketFactory;
use SlmQueueBeanstalkd\Options\ConnectionOptions;

class PheanstalkConnectionFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Connection
    {
        $config        = $container->get('config');
        if ($config && isset($config['beanstalkd']['connection'])) {
            $options = $config['beanstalkd']['connection'];
        }

        $connectionOptions = new ConnectionOptions($options);

        $socketFactory = new SocketFactory(
            $connectionOptions->getHost(),
            $connectionOptions->getPort(),
            $connectionOptions->getTimeout()
        );

        $connection = new Connection($socketFactory);

        return $connection;
    }
}