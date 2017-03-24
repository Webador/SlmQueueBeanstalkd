<?php

namespace SlmQueueBeanstalkdTest\Options;

use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;
use SlmQueueBeanstalkd\Options\ConnectionOptions;
use SlmQueueBeanstalkdTest\Util\ServiceManagerFactory;
use Zend\ServiceManager\ServiceManager;

class BeanstalkdOptionsTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        parent::setUp();
        $this->serviceManager = ServiceManagerFactory::getServiceManager();
    }

    public function testCreateBeanstalkdOptions()
    {
        /** @var $beanstalkdOptions \SlmQueueBeanstalkd\Options\BeanstalkdOptions */
        $beanstalkdOptions = $this->serviceManager->get(BeanstalkdOptions::class);
        $connectionOptions = $beanstalkdOptions->getConnection();

        $this->assertInstanceOf(BeanstalkdOptions::class, $beanstalkdOptions);
        $this->assertInstanceOf(ConnectionOptions::class, $connectionOptions);
        $this->assertEquals('0.0.0.0', $connectionOptions->getHost());
        $this->assertEquals(11300, $connectionOptions->getPort());
        $this->assertEquals(2, $connectionOptions->getTimeout());
    }
}
