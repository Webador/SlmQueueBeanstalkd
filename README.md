SlmQueueBeanstalkd
==================

[![Build Status](https://travis-ci.org/juriansluiman/SlmQueueBeanstalkd.png?branch=master)](https://travis-ci.org/juriansluiman/SlmQueueBeanstalkd)

Version 0.2.0 Created by Jurian Sluiman and Michaël Gallego


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
	"juriansluiman/slm-queue-beanstalkd": ">=0.2"
}
```

Then, enable the module by adding `SlmQueueBeanstalkd` in your application.config.php file. You may also want to
configure the module: just copy the `slm_queue_beanstalkd.local.php.dist` (you can find this file in the config
folder of SlmQueueBeanstalkd) into your config/autoload folder, and override what you want.
