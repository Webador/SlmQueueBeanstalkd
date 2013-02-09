<?php

namespace SlmQueueBeanstalkdTest\Worker;

use Exception;
use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueBeanstalkdTest\Asset;
use SlmQueueBeanstalkd\Worker\Worker as BeanstalkdWorker;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;
use Zend\ServiceManager\ServiceManager;

class WorkerTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var \SlmQueueBeanstalkd\Queue\TubeInterface
     */
    protected $queueMock;

    /**
     * @var BeanstalkdWorker
     */
    protected $worker;


    public function setUp()
    {
        parent::setUp();
        $this->serviceManager = ServiceManagerFactory::getServiceManager();

        $this->queueMock  = $this->getMock('SlmQueueBeanstalkd\Queue\TubeInterface');
        $queueManagerMock = $this->getMock('SlmQueue\Queue\QueuePluginManager');
        $workerOptions    = $this->serviceManager->get('SlmQueue\Options\WorkerOptions');

        $this->worker = new BeanstalkdWorker($queueManagerMock, $workerOptions);
    }

    public function testAssertJobIsDeletedIfNoExceptionIsThrown()
    {
        $job = new Asset\SimpleJob();

        $this->queueMock->expects($this->once())
            ->method('delete')
            ->with($job)
            ->will($this->returnCallback(function() use ($job) {
                $job->setContent('deleted');
            })
        );

        $this->worker->processJob($job, $this->queueMock);

        $this->assertEquals('deleted', $job->getContent());
    }

    public function testAssertJobIsReleasedIfReleasableExceptionIsThrown()
    {
        $job = new Asset\ReleasableJob();

        $this->queueMock->expects($this->once())
            ->method('release')
            ->with($job)
            ->will($this->returnCallback(function() use ($job) {
                $job->setContent('released');
            })
        );

        $this->worker->processJob($job, $this->queueMock);

        $this->assertEquals('released', $job->getContent());
    }

    public function testAssertJobIsBuriedIfBuryableExceptionIsThrown()
    {
        $job = new Asset\BuryableJob();

        $this->queueMock->expects($this->once())
            ->method('bury')
            ->with($job)
            ->will($this->returnCallback(function() use ($job) {
                $job->setContent('buried');
            })
        );

        $this->worker->processJob($job, $this->queueMock);

        $this->assertEquals('buried', $job->getContent());
    }

    public function testAssertJobIsBuriedIfAnyExceptionIsThrown()
    {
        $job = new Asset\ExceptionJob();

        $this->queueMock->expects($this->once())
            ->method('bury')
            ->with($job)
            ->will($this->returnCallback(function() use ($job) {
                $job->setContent('buried');
            })
        );

        $this->worker->processJob($job, $this->queueMock);

        $this->assertEquals('buried', $job->getContent());
    }
}
