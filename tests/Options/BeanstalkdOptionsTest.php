<?php

use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use SlmQueue\Queue\QueuePluginManager;
use SlmQueue\Strategy\MaxRunsStrategy;
use SlmQueue\Strategy\ProcessQueueStrategy;
use SlmQueueBeanstalkd\Options\ConnectionOptions;
use SlmQueueTest\Asset\SimpleController;
use SlmQueueTest\Asset\SimpleQueueFactory;
use SlmQueueTest\Asset\SimpleWorker;

class ConnectionOptionsTest extends TestCase
{

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var QueuePluginManager
     */
    protected $queuePluginManager;

    /**
     * @var SimpleController
     */
    protected $controller;

    public function setUp(): void
    {
        $worker = new SimpleWorker();

        $eventManager = $worker->getEventManager();
        (new ProcessQueueStrategy())->attach($eventManager);
        (new MaxRunsStrategy(['max_runs' => 1]))->attach($eventManager);
        $serviceManager = new ServiceManager();
        $config = [
            'factories' => [
                'knownQueue' => SimpleQueueFactory::class,
            ],
        ];

        $this->queuePluginManager = new QueuePluginManager($serviceManager, $config);
        $this->controller = new SimpleController($worker, $this->queuePluginManager);

        $this->serviceManager = $serviceManager;
    }

    public function testCreateConnectionOptions()
    {
        $connectionOptions = new ConnectionOptions();

        $this->assertEquals('0.0.0.0', $connectionOptions->getHost());
        $this->assertEquals(11300, $connectionOptions->getPort());
        $this->assertEquals(2, $connectionOptions->getTimeout());
    }
}