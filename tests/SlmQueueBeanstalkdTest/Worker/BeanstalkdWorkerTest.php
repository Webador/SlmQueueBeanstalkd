<?php

namespace SlmQueueBeanstalkdTest\Worker;

use PHPUnit_Framework_TestCase as TestCase;
use SlmQueue\Worker\Event\ProcessJobEvent;
use SlmQueueBeanstalkd\Worker\BeanstalkdWorker;

class BeanstalkdWorkerTest extends TestCase
{
    /**
     * @var BeanstalkdWorker
     */
    protected $worker;

    public function setUp()
    {
        $this->worker = new BeanstalkdWorker($this->getMock('Zend\EventManager\EventManagerInterface'));
    }

    public function testReturnsUnknownIfNotABeanstalkdQueue()
    {
        $queue = $this->getMock('SlmQueue\Queue\QueueInterface');
        $job   = $this->getMock('SlmQueue\Job\JobInterface');

        $status = $this->worker->processJob($job, $queue);
        $this->assertEquals(ProcessJobEvent::JOB_STATUS_UNKNOWN, $status);
    }

    public function testDeleteJobOnSuccess()
    {
        $queue = $this->getMock('SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface');
        $job   = $this->getMock('SlmQueue\Job\JobInterface');

        $job->expects($this->once())
            ->method('execute');

        $queue->expects($this->once())
              ->method('delete')
              ->with($job);

        $status = $this->worker->processJob($job, $queue);
        $this->assertEquals(ProcessJobEvent::JOB_STATUS_SUCCESS, $status);
    }

    public function testDoNotDeleteJobOnFailure()
    {
        $queue = $this->getMock('SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface');
        $job   = $this->getMock('SlmQueue\Job\JobInterface');

        $job->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \RuntimeException()));

        $queue->expects($this->never())
              ->method('delete');

        $status = $this->worker->processJob($job, $queue);
        $this->assertEquals(ProcessJobEvent::JOB_STATUS_FAILURE_RECOVERABLE, $status);
    }
}
