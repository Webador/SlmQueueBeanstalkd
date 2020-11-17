<?php

use Pheanstalk\Exception\JobNotFoundException;
use SlmQueueBeanstalkd\Factory\BeanstalkdQueueFactory;
use SlmQueueBeanstalkdTest\Asset\SimplePheanstalkJob;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;

class LiveTest extends \PHPUnit\Framework\TestCase {
    /**
     * @var SlmQueueBeanstalkd\Queue\
     */
    public $queue;

    public function setUp(): void {

        $sm = ServiceManagerFactory::getServiceManager();
        $factory = new BeanstalkdQueueFactory();
        $service = $factory($sm, null);

        $this->queue = $service;
    }

    public function testPushAndPeek()
    {
        $job1 = new SimplePheanstalkJob();
        $job1->setId(1);

        $job2 = new SimplePheanstalkJob();
        $job2->setId(2);

        $this->queue->push($job1);
        $this->queue->push($job2);

        $info = $this->queue->peek(2);

        static::assertInstanceOf(\SlmQueue\Job\JobInterface::class, $info);
        static::assertIsInt($info->getId());
        static::assertEquals(2, $info->getId());
    }

    public function testPop()
    {
        $job1 = new SimplePheanstalkJob();
        $this->queue->push($job1);

        $job = $this->queue->pop();
        static::assertNotNull($job);
        static::assertEquals(1, $job ? $job->getId() : null);
    }

    public function x_testRelease() {
        $pop = $this->queue->pop();

        $this->queue->release($pop);
    }

    public function testDelete() {
        $job2 = new SimplePheanstalkJob();
        $this->queue->push($job2);

        $id = $job2->getId();
        $peek = $this->queue->peek($id);

        $this->queue->delete($peek);

        $this->expectException(JobNotFoundException::class);
        $this->queue->peek($id);
    }
}