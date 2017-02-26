<?php
namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Options\QueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * BeanstalkdQueueFactory
 */
class BeanstalkdQueueFactory implements FactoryInterface
{
	public function __invoke(
		ContainerInterface $container,
		$requestedName,
		array $options = null
	) {
		$pheanstalk       = $container->get('SlmQueueBeanstalkd\Service\PheanstalkService');
		$jobPluginManager = $container->get('SlmQueue\Job\JobPluginManager');

		$queueOptions = $this->getQueueOptions($container, $requestedName);

		return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
	}

    /**
     * Returns custom beanstalkd options for specified queue
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $queueName
     * @return QueueOptions
     */
    protected function getQueueOptions(\Zend\ServiceManager\ServiceManager $serviceLocator, $queueName)
    {
        $config = $serviceLocator->get('Config');
        $queuesOptions = isset($config['slm_queue']['queues'])? $config['slm_queue']['queues'] : array();
        $queueOptions = isset($queuesOptions[$queueName])? $queuesOptions[$queueName] : array();

        return new QueueOptions($queueOptions);
    }
}
