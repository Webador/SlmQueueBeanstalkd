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
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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
        return 'SlmQueueBeanstalkd ' . Version::VERSION;
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
     * This ModuleManager feature was introduced in ZF 2.1 to check if all the dependencies needed by a module
     * were correctly loaded. However, as we want to keep backward-compatibility with ZF 2.0, please DO NOT
     * explicitely implement Zend\ModuleManager\Feature\DependencyIndicatorInterface. Just write this method and
     * the module manager will automatically call it
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return array('SlmQueue');
    }
}
