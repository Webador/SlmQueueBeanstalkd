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

        $this->assertInstanceOf('SlmQueueBeanstalkd\Options\BeanstalkdOptions', $beanstalkdOptions);
    }
}
