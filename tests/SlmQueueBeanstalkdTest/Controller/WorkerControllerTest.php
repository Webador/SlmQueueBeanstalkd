<?php

namespace SlmQueueBeanstalkdTest\Options;

use Pheanstalk_Job;
use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;

class WorkerControllerTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Pheanstalk_Pheanstalk
     */
    protected $pheanstalkMock;


    public function setUp()
    {
        parent::setUp();
        $this->serviceManager = ServiceManagerFactory::getServiceManager();

        $this->pheanstalkMock = $this->getMock('Pheanstalk_Pheanstalk', array(), array(), '', false);
        $this->serviceManager->setAllowOverride(true);
        $pheanstalk = $this->pheanstalkMock;
        $this->serviceManager->setFactory('SlmQueueBeanstalkd\Service\PheanstalkService', function() use ($pheanstalk) {
            return $pheanstalk;
        });
    }

    public function testThrowExceptionIfQueueIsUnknown()
    {
        $controller = $this->serviceManager->get('ControllerLoader')->get('SlmQueueBeanstalkd\Controller\Worker');
        $routeMatch = new RouteMatch(array('queue' => 'unknown'));
        $controller->getEvent()->setRouteMatch($routeMatch);

        $this->setExpectedException('Zend\ServiceManager\Exception\ServiceNotFoundException');
        $result = $controller->processAction();
    }

    public function testCorrectlyCountJobs()
    {
        $controller = $this->serviceManager->get('ControllerLoader')->get('SlmQueueBeanstalkd\Controller\Worker');
        $routeMatch = new RouteMatch(array('queue' => 'newsletter'));
        $controller->getEvent()->setRouteMatch($routeMatch);

        $pheanstalkJob = new Pheanstalk_Job(4, '{"class":"SlmQueueBeanstalkdTest\\\Asset\\\SimpleJob","content":"Foo"}');

        $this->pheanstalkMock->expects($this->once())
             ->method('reserveFromTube')
             ->will($this->returnValue($pheanstalkJob));

        $result = $controller->processAction();

        $this->assertContains('newsletter', $result);
        $this->assertContains('finished', strtolower($result));
        $this->assertContains('1', $result);
    }
}
