<?php

namespace SlmQueueBeanstalkdTest\Queue;

use Pheanstalk\Job as PheanstalkJob;
use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use SlmQueueBeanstalkdTest\Asset\SimpleJob;

/**
 * BeanstalkdQueue Test for custom tube name
 */
class BeanstalkdQueueTubeTest extends TestCase
{
    /**
     * @var string
     */
    protected $queueName;
    /**
     * @var string
     */
    protected $tubeName;
    /**
     * @var BeanstalkdQueue
     */
    protected $queue;
    /**
     * @var \Pheanstalk\Pheanstalk|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pheanstalk;
    /**
     * @var \SlmQueue\Job\JobPluginManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pluginManager;

    public function setUp()
    {
        $this->queueName  = 'testQueueName';
        $this->tubeName  = 'testQueueTubeName';
        $this->pheanstalk = $this->getMockBuilder('Pheanstalk\Pheanstalk')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->pluginManager = $this->getMockBuilder('SlmQueue\Job\JobPluginManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->queue = new BeanstalkdQueue($this->pheanstalk, $this->queueName, $this->pluginManager, $this->tubeName);
    }

    public function testTubeNameGetter()
    {
        $tubeName = $this->tubeName;
        $result = $this->queue->getTubeName();
        $this->assertEquals($result, $tubeName);
    }

    public function testSuccessfulKickWithSelectedTube()
    {
        $maxKick    = 10;
        $tubeName  = $this->tubeName;
        $pheanstalk = $this->pheanstalk;

        $pheanstalk->expects($this->once())
                   ->method('useTube')
                   ->with($this->equalTo($tubeName))
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
        $tubeName      = $this->tubeName;
        $pluginManager  = $this->pluginManager;

        $job            = new SimpleJob;
        $job->setMetadata('foo', 'bar');

        $pheanstalkJob = new PheanstalkJob(1, $this->queue->serializeJob($job));

        $pheanstalk->expects($this->once())
                   ->method('reserveFromTube')
                   ->with($this->equalTo($tubeName))
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
