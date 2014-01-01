# 0.3.0

- Update to SlmQueue 0.3
- [BC] BeanstalkdQueueInterface have been renamed to TubeInterface, BeanstalkdQueue have been renamed to Tube

# 0.2.1

- Fix compatibilities problems with PHP 5.3

# 0.2.0

- This version is a complete rewrite of SlmQueue. It is now splitted in several modules and support both
Beanstalkd and Amazon SQS queue systems through SlmQueueBeanstalkd and SlmQueueSqs modules.
