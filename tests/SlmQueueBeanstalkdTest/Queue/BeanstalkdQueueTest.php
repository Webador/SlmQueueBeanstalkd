<?php

namespace SlmQueueBeanstalkdTest\Queue;

use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use SlmQueueBeanstalkdTest\Asset\SimpleJob;
use Pheanstalk_Job;

/**
 * BeanstalkdQueue Test
 */
class BeanstalkdQueueTest extends TestCase
{
    protected $queueName;
    protected $pheanstalk;
    protected $pluginManager;

    public function setUp()
    {
        $this->queueName  = 'testQueueName';
        $this->pheanstalk = $this->getMockBuilder('Pheanstalk_Pheanstalk')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->pluginManager = $this->getMockBuilder('SlmQueue\Job\JobPluginManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->queue = new BeanstalkdQueue($this->pheanstalk, $this->queueName, $this->pluginManager);
    }

    public function testSuccessfulKickWithSelectedTube()
    {
        $maxKick    = 10;
        $queueName  = $this->queueName;
        $pheanstalk = $this->pheanstalk;

        $pheanstalk->expects($this->once())
                   ->method('useTube')
                   ->with($this->equalTo($queueName))
                   ->will($this->returnValue($pheanstalk));

        $pheanstalk->expects($this->once())
                   ->method('kick')
                   ->with($this->equalTo($maxKick))
                   ->will($this->returnValue($maxKick));

        $result = $this->queue->kick($maxKick);
        $this->assertEquals($result, $maxKick);
    }

    public function testPopPreservesMetadata()
    {
        $pheanstalk     = $this->pheanstalk;
        $queueName      = $this->queueName;
        $pluginManager  = $this->pluginManager;

        $job            = new SimpleJob;
        $job->setMetadata('foo', 'bar');

        $pheanstalk_job = new Pheanstalk_Job(1, $job->jsonSerialize());

        $pheanstalk->expects($this->once())
                   ->method('reserveFromTube')
                   ->with($this->equalTo($queueName))
                   ->will($this->returnValue($pheanstalk_job));

        $pluginManager->expects($this->once())
                      ->method('get')
                      ->with(get_class($job))
                      ->will($this->returnValue($job));

        $result = $this->queue->pop();

        $this->assertEquals($result, $job);
        $this->assertEquals('bar', $job->getMetadata('foo'));
    }
}
