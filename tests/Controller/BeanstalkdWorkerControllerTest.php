<?php

namespace SlmQueueBeanstalkdTest\Controller;

use Laminas\Mvc\Controller\ControllerManager;
use Laminas\Mvc\Router\RouteMatch;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use SlmQueue\Queue\QueuePluginManager;
use SlmQueue\Strategy\MaxRunsStrategy;
use SlmQueue\Strategy\ProcessQueueStrategy;
use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;

use SlmQueueTest\Asset\SimpleController;
use SlmQueueBeanstalkdTest\Asset\SimplePheanstalkJob;
use SlmQueueTest\Asset\SimpleQueue;
use SlmQueueTest\Asset\SimpleQueueFactory;
use SlmQueueTest\Asset\SimpleWorker;

class BeanstalkdWorkerControllerTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = ServiceManagerFactory::getServiceManager();


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
    }

    public function testThrowExceptionIfQueueIsUnknown(): void
    {
        $manager = $this->serviceManager->get('ControllerManager');

        $controller = $this->serviceManager->get('ControllerManager')->get(BeanstalkdWorkerController::class);
        $routeMatch = new RouteMatch(['queue' => 'unknownQueue']);
        $controller->getEvent()->setRouteMatch($routeMatch);

        $this->expectException(ServiceNotFoundException::class);

        $controller->processAction();
    }

    public function testCorrectlyCountJobs()
    {
        /** @var SimpleQueue $queue */
        $queue = $this->queuePluginManager->get('knownQueue');
        $queue->push(new SimplePheanstalkJob());

        $routeMatch = new RouteMatch(['queue' => 'knownQueue']);
        $this->controller->getEvent()->setRouteMatch($routeMatch);

        $result = $this->controller->processAction();
        static::assertStringContainsString("Finished worker for queue 'knownQueue'", $result);
        static::assertStringContainsString("maximum of 1 jobs processed", $result);
    }
}