<?php

namespace SlmQueueBeanstalkd;

use Zend\Loader;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature;

/**
 * SlmQueueBeanstalkd
 */
class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ConsoleBannerProviderInterface,
    Feature\ConsoleUsageProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            Loader\AutoloaderFactory::STANDARD_AUTOLOADER => array(
                Loader\StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'SlmQueueBeanstalkd';
    }

    /**
     * {@inheritDoc}
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            'queue beanstalkd <queue> [--timeout=]' => 'Process jobs with beanstalkd',

            array('<queue>', 'Queue\'s name to process'),
            array('--timeout=', 'Timeout (in seconds) to wait for a job to arrive')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getModuleDependencies()
    {
        return array('SlmQueue');
    }
}
