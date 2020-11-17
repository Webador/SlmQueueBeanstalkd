<?php

namespace SlmQueueBeanstalkdTest\Queue;

use Pheanstalk\Job as PheanstalkJob;
use Pheanstalk\Pheanstalk;
use PHPUnit\Framework\TestCase as TestCase;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use SlmQueueBeanstalkdTest\Asset\SimplePheanstalkJob;

/**
 * BeanstalkdQueue Test
 */
class BeanstalkdQueueTest extends TestCase
{
    protected $queueName;
    protected $pheanstalk;
    protected $pluginManager;

    public function setUp(): void
    {
        $this->queueName  = 'testQueueName';
        $this->pheanstalk = $this->getMockBuilder('Pheanstalk\Pheanstalk')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->pluginManager = $this->getMockBuilder('SlmQueue\Job\JobPluginManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->queue = new BeanstalkdQueue($this->pheanstalk, $this->queueName, $this->pluginManager);
    }

    public function testTubeNameGetter()
    {
        $tubeName = $this->queueName;
        $result = $this->queue->getTubeName();
        $this->assertEquals($result, $tubeName);
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

        $job            = new SimplePheanstalkJob;
        $job->setMetadata('foo', 'bar');

        $pheanstalkJob = new PheanstalkJob(1, $this->queue->serializeJob($job));

        $pheanstalk->expects($this->once())
                   ->method('reserve')
                   ->will($this->returnValue($pheanstalkJob));

        $pluginManager->expects($this->once())
                      ->method('get')
                      ->with(get_class($job))
                      ->will($this->returnValue($job));

        $result = $this->queue->pop();

        $this->assertEquals($result, $job);
        $this->assertEquals('bar', $job->getMetadata('foo'));
    }
}
