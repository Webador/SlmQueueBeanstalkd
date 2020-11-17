<?php

namespace SlmQueueBeanstalkd;

use Laminas\Console\Adapter\AdapterInterface;
use Laminas\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Laminas\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Laminas\Console\Adapter\AdapterInterface as Console;

/**
 * SlmQueueBeanstalkd
 */
class Module implements ConsoleBannerProviderInterface, ConsoleUsageProviderInterface
{
    const VERSION = '2.0.0-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Returns a string containing a banner text, that describes the module and/or the application.
     * The banner is shown in the console window, when the user supplies invalid command-line parameters or invokes
     * the application with no parameters.
     *
     * The method is called with active Laminas\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     * @param AdapterInterface $console
     * @return string|null
     */
    public function getConsoleBanner(Console $console)
    {
        return 'SlmQueueBeanstalkd Module v.' . self::VERSION;
    }

    /**
     * Returns an array or a string containing usage information for this module's Console commands.
     * The method is called with active Laminas\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     * If the result is a string it will be shown directly in the console window.
     * If the result is an array, its contents will be formatted to console window width. The array must
     * have the following format:
     *
     *     return array(
     *                'Usage information line that should be shown as-is',
     *                'Another line of usage info',
     *
     *                '--parameter'        =>   'A short description of that parameter',
     *                '-another-parameter' =>   'A short description of another parameter',
     *                ...
     *            )
     *
     * @param AdapterInterface $console
     * @return array|string|null
     */
    public function getConsoleUsage(Console $console)
    {
        return [
            'queue beanstalkd <queue> --start [--timeout=]'             => 'Process Beanstalkd queue',
            'queue beanstalkd <queue> --recover [--executionTime=]'     => 'Recover Beanstalkd worker',
        ];
    }
}