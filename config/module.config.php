<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'SlmQueueBeanstalkd\Service\PheanstalkService' => 'SlmQueueBeanstalkd\Factory\PheanstalkFactory',
            'SlmQueueBeanstalkd\Worker\Worker'             => 'SlmQueueBeanstalkd\Factory\WorkerFactory'
        )
    ),

    'console'   => array(
        'router' => array(
            'routes' => array(
                'slm-queue-beanstalked-worker' => array(
                    'type'    => 'Simple',
                    'options' => array(
                        'route'    => 'queue beanstalkd --queueName= --start',
                        'defaults' => array(
                            'controller' => 'SlmQueueBeanstalkd\Controller\Worker',
                            'action'     => 'process'
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'SlmQueueBeanstalkd\Controller\Worker'         => 'SlmQueueBeanstalkd\Controller\WorkerController'
        )
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
