<?php
namespace SlmQueueBeanstalkd\Factory;

use Pheanstalk\Pheanstalk;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * PheanstalkFactory
 */
class PheanstalkFactory implements FactoryInterface
{
	public function __invoke(
		ContainerInterface $container,
		$requestedName,
		array $options = null
	) {
		$beanstalkdOptions = $container->get('SlmQueueBeanstalkd\Options\BeanstalkdOptions');
		$connectionOptions = $beanstalkdOptions->getConnection();

		return new Pheanstalk(
			$connectionOptions->getHost(),
			$connectionOptions->getPort(),
			$connectionOptions->getTimeout()
		);
	}
}
