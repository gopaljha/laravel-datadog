# Laravel DataDog

A simple package to use DataDog Series Metric using their API via TCP 

## Why?

Because some people cannot install the DataDog Agent or StatsD. So we have to use DataDog API to send data. Using the API losses the advantage of using UDP (unblocking) calls. This package gives you a nice way to send metric information and also make sure the jobs are queued.

## Other packages

This package should only be used if you also find yourslef in the unique situation where you cannot use the DataDog Agent. Make sure you investigate the below packages first.

- https://github.com/DataDog/php-datadogstatsd
- https://github.com/chaseconey/laravel-datadog-helper

## Installation

Pull in the package using Composer 

```
composer require gopaljha/laravel-datadog
```

Publish the config file 

```php
php artisan vendor:publish --provider="GopalJha\LaravelDataDog\LaravelDataDogServiceProvider" --tag=config
```

Set your DataDog API key in your `.env` file using the key `DATADOG_KEY`.

 ## How to use

### Increment a Metric

```php
\DataDog::increment('app.pageview');
```

### Increment a Metric with tagging and Host

A powerful feature of DataDog is the ability to tag things.

```php
\DataDog::increment('app.pageview', ['my:tag:one', 'my:tag:two']);
```

You can also send a custom host if you require.
```php
\DataDog::increment('app.pageview', ['my:tag:one', 'my:tag:two'], 'my.host.com');
```
