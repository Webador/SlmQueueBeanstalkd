<?php
/**
 * Copyright (c) 2017 Geil.PM
 */

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Options\BeanstalkdOptions;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class BeanstalkdOptionsFactory implements FactoryInterface
{
	public function __invoke(
		ContainerInterface $container,
		$requestedName,
		array $options = null
	) {
		$config = $container->get('Config');
		return new BeanstalkdOptions($config['slm_queue']['beanstalkd']);
	}
}
