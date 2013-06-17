<?php

namespace SlmQueueBeanstalkdTest\Queue;

use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;

/**
 * BeanstalkdQueue Test
 */
class BeanstalkdQueueTest extends TestCase
{
    public function testSuccessfulKickWithSelectedTube()
    {
        $queueName = 'testQueueName';
        $maxKick = 10;
        $pheanstalk = $this->getMockBuilder('Pheanstalk_Pheanstalk')
                           ->disableOriginalConstructor()
                           ->getMock();

        $pheanstalk->expects($this->once())
                   ->method('useTube')
                   ->with($this->equalTo($queueName))
                   ->will($this->returnValue($pheanstalk));
        $pheanstalk->expects($this->once())
                   ->method('kick')
                   ->with($this->equalTo($maxKick))
                   ->will($this->returnValue($maxKick));

        $jobPluginManager = $this->getMockBuilder('SlmQueue\Job\JobPluginManager')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $queue = new BeanstalkdQueue($pheanstalk, $queueName, $jobPluginManager);
        $result = $queue->kick($maxKick);

        $this->assertEquals($result, $maxKick);
    }
}
