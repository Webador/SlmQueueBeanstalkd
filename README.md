SlmQueueBeanstalkd
==================

[![Build Status](https://travis-ci.org/juriansluiman/SlmQueueBeanstalkd.png?branch=master)](https://travis-ci.org/juriansluiman/SlmQueueBeanstalkd)
[![Latest Stable Version](https://poser.pugx.org/slm/queue-beanstalkd/v/stable.png)](https://packagist.org/packages/slm/queue-beanstalkd)
[![Latest Unstable Version](https://poser.pugx.org/slm/queue-beanstalkd/v/unstable.png)](https://packagist.org/packages/slm/queue-beanstalkd)


Created by Jurian Sluiman and Michaël Gallego


Requirements
------------
* [Zend Framework 2](https://github.com/zendframework/zf2)
* [SlmQueue](https://github.com/juriansluiman/SlmQueue)
* [Pda Pheanstalk](https://github.com/pda/pheanstalk)


Installation
------------

First, install SlmQueue ([instructions here](https://github.com/juriansluiman/SlmQueue/blob/master/README.md)). Then,
add the following line into your `composer.json` file:

```json
"require": {
	"slm/queue-beanstalkd": "0.3.*"
}
```

Then, enable the module by adding `SlmQueueBeanstalkd` in your application.config.php file. You may also want to
configure the module: just copy the `slm_queue_beanstalkd.global.php.dist` (you can find this file in the config
folder of SlmQueueBeanstalkd) into your config/autoload folder, and override what you want.


Documentation
-------------

Before reading SlmQueueBeanstalkd documentation, please read [SlmQueue documentation](https://github.com/juriansluiman/SlmQueue).

(Don't forget to first install Beanstalkd, and to run the daemon program on the server)


### Setting the connection parameters

Copy the `slm_queue_beanstalkd.local.php.dist` file to your `config/autoload` folder, and follow the instructions.


### Adding queues

SlmQueueBeanstalkd provides an interface for a queue that implements `SlmQueue\Queue\QueueInterface` and provides in
addition the following methods:

* release(JobInterface $job, array $options = array()): when a job fails, you can add the job again to the queue
by releasing it, so that it can have another chance to be executed.
* bury(JobInterface $job, array $options = array()): when a job fails and that it has not been manually released, it
is automatically buried.
* kick($max): when this method is called, it will move a maximum of $max buried jobs back to the queue.

A concrete class that implements this interface is included: `SlmQueueBeanstalkd\Queue\Tube` and a factory is available to
create the queue. Therefore, if you want to have a queue called "email", just add the following line in your
`module.config.php` file:

```php
return array(
    'slm_queue' => array(
        'queue_manager' => array(
            'factories' => array(
                'newsletter' => 'SlmQueueBeanstalkd\Factory\TubeFactory'
            )
        )
    )
);
```

This queue can therefore be pulled from the QueuePluginManager class.

### Operations on queues

#### push

Valid options are:

* priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
* delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)
* ttr: in seconds, how much time a job can be reserved for (default to 60)

Example:

```php
$queue->push($job, array(
    'priority' => 20,
    'delay'    => 23,
    'ttr'      => 50
));
```

#### pop

Valid option is:

* timeout: by default, when we ask for a job, it will block until a job is found (possibly forever if new jobs never
come). If you set a timeout (in seconds), it will return after the timeout is expired, even if no jobs were found

#### release

Valid options are:

* priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
* delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)

#### bury

Valid option is:

* priority: the lower the priority is, the sooner the job get kicked


### How to bury/release a job

Beanstalkd offers a nice bury/kick/release mechanism, so that jobs that fail can have a second chance to be executed.
SlmQueueBeanstalkd provides a nice way to easily bury/release a job. In fact, you just need to throw either
the `SlmQueueBeanstalkd\Job\Exception\BuryableException` or `SlmQueueBeanstalkd\Job\Exception\ReleasableException` in
the `execute` method of your job:

```php
use SlmQueue\Job\AbstractJob;
use SlmQueueBeanstalkd\Job\Exception;

class SimpleJob extends AbstractJob
{
    public function execute()
    {
        // Bury the job, with a priority of 10
        throw new Exception\BuryableException(array('priority' => 10));

        // Release the job, with a priority of 10 and delay of 5 seconds
        throw new Exception\ReleasableException(array('priority' => 10, 'delay' => 5));
    }
}
```


### Executing jobs

SlmQueueBeanstalkd provides a command-line tool that can be used to pop and execute jobs. You can type the following
command within the public folder of your Zend Framework 2 application:

`php index.php queue beanstalkd <queue> [--timeout=]`

The queue is a mandatory parameter, while the timeout is an optional flag that specifies the duration in seconds
for which the call will wait for a job to arrive in the queue before returning (because the script can wait forever
if no job come).
