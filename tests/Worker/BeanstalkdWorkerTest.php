<?php

namespace SlmQueueBeanstalkdTest\Worker;

use PHPUnit\Framework\TestCase as TestCase;
use SlmQueue\Worker\Event\ProcessJobEvent;
use SlmQueue\Worker\WorkerEvent;
use SlmQueueBeanstalkd\Job\Exception\ReleasableException;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface;
use SlmQueueBeanstalkd\Worker\BeanstalkdWorker;

class BeanstalkdWorkerTest extends TestCase
{
    /**
     * @var BeanstalkdWorker
     */
    protected $worker;

    public function setUp(): void
    {
        $this->worker = new BeanstalkdWorker($this->getMockBuilder('Laminas\EventManager\EventManagerInterface')->getMock());
    }

    public function testReturnsUnknownIfNotABeanstalkdQueue()
    {
        $queue = $this->getMockBuilder('SlmQueue\Queue\QueueInterface')->getMock();
        $job   = $this->getMockBuilder('SlmQueue\Job\JobInterface')->getMock();

        $status = $this->worker->processJob($job, $queue);

        $this->assertEquals(ProcessJobEvent::JOB_STATUS_UNKNOWN, $status);
    }

    public function testDeleteJobOnSuccess()
    {
        $queue = $this->getMockBuilder(BeanstalkdQueueInterface::class)->getMock();
        $job   = $this->getMockBuilder('SlmQueue\Job\JobInterface')->getMock();

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
        $queue = $this->getMockBuilder(BeanstalkdQueueInterface::class)->getMock();
        $job   = $this->getMockBuilder('SlmQueue\Job\JobInterface')->getMock();

        $job->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new ReleasableException()));

        $queue->expects($this->never())
              ->method('delete');

        $status = $this->worker->processJob($job, $queue);
        $this->assertEquals(ProcessJobEvent::JOB_STATUS_FAILURE_RECOVERABLE, $status);
    }
}
