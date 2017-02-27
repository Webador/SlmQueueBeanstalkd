<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * BeanstalkdWorkerControllerFactory
 */
class BeanstalkdWorkerControllerFactory implements FactoryInterface
{
	public function __invoke(
		ContainerInterface $container,
		$requestedName,
		array $options = null
	) {
		$worker  = $container->get('SlmQueueBeanstalkd\Worker\BeanstalkdWorker');
		$manager = $container->get('SlmQueue\Queue\QueuePluginManager');

		return new BeanstalkdWorkerController($worker, $manager);
	}
}
