<?php

namespace SlmQueueBeanstalkdTest;

use PHPUnit\Framework\TestCase as TestCase;
use SlmQueueBeanstalkd\ConfigProvider;
use SlmQueueBeanstalkd\Module;

class ConfigProviderTest extends TestCase
{
    public function testConfigProviderGetConfig(): void
    {
        $configProvider = new ConfigProvider();
        $config = $configProvider();

        static::assertNotEmpty($config);
    }

    public function testConfigEqualsToModuleConfig(): void
    {
        $module = new Module();
        $moduleConfig = $module->getConfig();
        $configProvider = new ConfigProvider();
        $config = $configProvider();

        static::assertEquals($moduleConfig['service_manager'], $config['dependencies']);
        static::assertEquals($moduleConfig['slm_queue'], $config['slm_queue']);
    }
}