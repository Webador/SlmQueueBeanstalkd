<?php

use SlmQueue\Strategy\MaxRunsStrategy;
use SlmQueueBeanstalkd\Factory\BeanstalkdQueueFactory;
use SlmQueue\Factory\WorkerFactory;
use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use SlmQueueBeanstalkd\Factory\BeanstalkdWorkerControllerFactory;
use SlmQueueBeanstalkd\Strategy\ClearObjectManagerStrategy;
use SlmQueueBeanstalkd\Worker\BeanstalkdWorker;

return [
    'service_manager' => [
        'factories' => [
            BeanstalkdWorker::class => WorkerFactory::class,
            \Pheanstalk::class => \SlmQueueBeanstalkd\Factory\PheanstalkFactory::class
        ]
    ],
    'controllers'     => [
        'factories' => [
            BeanstalkdWorkerController::class => BeanstalkdWorkerControllerFactory::class,
            \Pheanstalk::class => \SlmQueueBeanstalkd\Factory\PheanstalkFactory::class
        ],
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'slm-queue-Beanstalkd-worker'  => [
                    'type'    => 'Simple',
                    'options' => [
                        'route'    => 'queue Beanstalkd <queue> [--timeout=] --start',
                        'defaults' => [
                            'controller' => BeanstalkdWorkerController::class,
                            'action'     => 'process'
                        ],
                    ],
                ],
                'slm-queue-Beanstalkd-recover' => [
                    'type'    => 'Simple',
                    'options' => [
                        'route'    => 'queue Beanstalkd <queue> --recover [--executionTime=]',
                        'defaults' => [
                            'controller' => BeanstalkdWorkerController::class,
                            'action'     => 'recover'
                        ],
                    ],
                ],
                'slm-queue-Beanstalkd-stats' => [
                    'type'    => 'Simple',
                    'options' => [
                        'route'    => 'queue Beanstalkd <queue> --stats',
                        'defaults' => [
                            'controller' => BeanstalkdWorkerController::class,
                            'action'     => 'stats'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'slm_queue' => [
        'worker_strategies' => [
            'default' => [
                MaxRunsStrategy::class => ['max_runs' => 1]
            ]
        ],
        'queues'            => [
            'my-beanstalkd-queue' => [
                'deleted_lifetime' => -1,
                'buried_lifetime'  => -1,
            ],
        ],
        'queue_manager'     => [
            'factories' => [
                'mailing' => BeanstalkdQueueFactory::class
            ]
        ],
    ],
    'beanstalkd' => array(
        'connection' => array(
            'host'    => '0.0.0.0',
            'port'    => 11300,
            'timeout' => 2
        )
    )
];