<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

use SlmQueue\Strategy\MaxRunsStrategy;
use SlmQueueBeanstalkd\Factory\BeanstalkdQueueFactory;
use SlmQueue\Factory\WorkerFactory;
use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use SlmQueueBeanstalkd\Factory\BeanstalkdWorkerControllerFactory;
use SlmQueueBeanstalkd\Strategy\ClearObjectManagerStrategy;
use SlmQueueBeanstalkd\Worker\BeanstalkdWorker;

return array(
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

    'service_manager' => [
        'factories' => [
            BeanstalkdWorker::class => WorkerFactory::class,
            \Pheanstalk::class => \SlmQueueBeanstalkd\Factory\PheanstalkFactory::class
        ]
    ],
    'controllers'     => [
        'factories' => [
            BeanstalkdWorkerController::class => BeanstalkdWorkerControllerFactory::class,
        ],
    ],
    'beanstalkd' => array(
        'connection' => array(
            'host'    => '0.0.0.0',
            'port'    => 11300,
            'timeout' => 2
        )
    )
);
