<?php

namespace SlmQueueBeanstalkdTest\Controller;

use Pheanstalk_Job;
use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use SlmQueueBeanstalkdTest\Asset\SimpleJob;

class BeanstalkdWorkerControllerTest extends TestCase
{
    public function testCorrectlyCountJobs()
    {
        $queue         = $this->getMock('SlmQueue\Queue\QueueInterface');
        $worker        = $this->getMock('SlmQueue\Worker\WorkerInterface');
        $pluginManager = $this->getMock('SlmQueue\Queue\QueuePluginManager', array(), array(), '', false);

        $pluginManager->expects($this->once())
                      ->method('get')
                      ->with('newsletter')
                      ->will($this->returnValue($queue));

        $controller    = new BeanstalkdWorkerController($worker, $pluginManager);

        $routeMatch = new RouteMatch(array('queue' => 'newsletter'));
        $controller->getEvent()->setRouteMatch($routeMatch);

        $worker->expects($this->once())
               ->method('processQueue')
               ->with($queue)
               ->will($this->returnValue(array('One state')));

        $result = $controller->processAction();

        $this->assertStringEndsWith("One state\n", $result);
    }
}
