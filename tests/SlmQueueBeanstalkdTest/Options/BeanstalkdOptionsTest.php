<?php

namespace SlmQueueBeanstalkdTest\Options;

use PHPUnit_Framework_TestCase as TestCase;
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
        $beanstalkdOptions = $this->serviceManager->get('SlmQueueBeanstalkd\Options\BeanstalkdOptions');
        $connectionOptions = $beanstalkdOptions->getConnection();

        $this->assertInstanceOf('SlmQueueBeanstalkd\Options\BeanstalkdOptions', $beanstalkdOptions);
        $this->assertInstanceOf('SlmQueueBeanstalkd\Options\ConnectionOptions', $connectionOptions);
        $this->assertEquals('0.0.0.0', $connectionOptions->getHost());
        $this->assertEquals(11300, $connectionOptions->getPort());
        $this->assertEquals(2, $connectionOptions->getTimeout());
    }
}
