<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'SlmQueueBeanstalkd\Options\BeanstalkdOptions' => 'SlmQueueBeanstalkd\Factory\BeanstalkdOptionsFactory',
            'SlmQueueBeanstalkd\Service\PheanstalkService' => 'SlmQueueBeanstalkd\Factory\PheanstalkFactory',
            'SlmQueueBeanstalkd\Worker\Worker'             => 'SlmQueueBeanstalkd\Factory\WorkerFactory'
        )
    ),

    'controllers' => array(
        'factories' => array(
            'SlmQueueBeanstalkd\Controller\Worker'         => 'SlmQueueBeanstalkd\Factory\WorkerControllerFactory',
        ),
    ),

    'console'   => array(
        'router' => array(
            'routes' => array(
                'slm-queue-beanstalked-worker' => array(
                    'type'    => 'Simple',
                    'options' => array(
                        'route'    => 'queue beanstalkd <queue> [--timeout=]',
                        'defaults' => array(
                            'controller' => 'SlmQueueBeanstalkd\Controller\Worker',
                            'action'     => 'process'
                        ),
                    ),
                ),
            ),
        ),
    ),

    'slm_queue' => array(
        'beanstalkd' => array(
            'connection' => array(
                'host'    => '0.0.0.0',
                'port'    => 11300,
                'timeout' => 2
            )
        )
    )
);
