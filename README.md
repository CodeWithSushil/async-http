# Async HTTP Client for PHP

[![Packagist Version](https://img.shields.io/packagist/v/async-http/async-http.svg)](https://packagist.org/packages/async-http/async-http)
[![Tests](https://github.com/CodeWithSushil/async-http/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/CodeWithSushil/async-http/actions/workflows/tests.yml)

A fully async, non-blocking HTTP client built using `stream_socket_client` and `stream_select`. No cURL. No Guzzle.

## Features

- 🌀 Non-blocking requests using PHP streams
- 🔁 Multiple async requests in parallel
- 🔄 Retries and timeout support
- 🧩 PSR-18 / PSR-7 compatible
- ✅ GET, POST, PUT, PATCH, DELETE supported

## Install

```bash
composer require async-http/async-http
```

## Example

```php
<?php

require_once('vendor/autoload.php');

use Async\Http\AsyncHttpClient;

$client = new AsyncHttpClient();
foreach ($client->get('https://jsonplaceholder.typicode.com/posts/1') as $response) {
    echo $response->getBody();
}
```

## Parallel Requests

```php
<?php

require_once('vendor/autoload.php');

use Async\Http\AsyncHttpClient;
use Async\Http\MultiAsyncHandler;

$client = new AsyncHttpClient();
$multi = new MultiAsyncHandler();

$urls = [
    'https://jsonplaceholder.typicode.com/posts/1',
    'https://jsonplaceholder.typicode.com/posts/2',
];

foreach ($urls as $url) {
    $multi->add(fn() => $client->get($url), function($res) use ($url) {
        echo "[$url] => " . substr($res->getBody(), 0, 80) . "\n";
    });
}

$multi->run();
```
