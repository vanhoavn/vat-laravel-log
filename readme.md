VZT Laravel Package
===================

Configurations
--------------

Add to `config\logging.php`

```php
    /**
     * Write the logs to stderr first
     **/
    'override_output'   => env('LOG_DEBUG', false),
    /**
     * Minimum logging levels: all levels below this will be ignored
     **/
    'min_logging_level' => env('MIN_LOGGING_LEVEL', 'notice'),
``````


With the above configs, you can debug loggings while running console commands by prepending `LOG_DEBUG=1`.

```bash
LOG_DEBUG=1 php artisan ...
```
