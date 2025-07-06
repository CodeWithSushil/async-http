# Async HTTP Client for PHP

[![Packagist Version](https://img.shields.io/packagist/v/codewithsushil/async-http-client.svg)](https://packagist.org/packages/codewithsushil/async-http-client)
[![CI](https://github.com/codewithsushil/async-http-client/actions/workflows/ci.yml/badge.svg)](https://github.com/codewithsushil/async-http-client/actions)

A fully async, non-blocking HTTP client built using `stream_socket_client` and `stream_select`. No cURL. No Guzzle.

## Features

- 🌀 Non-blocking requests using PHP streams
- 🔁 Multiple async requests in parallel
- 🔄 Retries and timeout support
- 🧩 PSR-18 / PSR-7 compatible
- ✅ GET, POST, PUT, PATCH, DELETE support
- 📦 Composer-ready

## Install

```bash
composer require codewithsushil/async-http-client
```

## Example

```php
use AsyncHttp\Http\AsyncHttpClient;

$client = new AsyncHttpClient();
foreach ($client->get('https://jsonplaceholder.typicode.com/posts/1') as $response) {
    echo $response->getBody();
}
```

## Parallel Requests

```php
use AsyncHttp\Http\AsyncHttpClient;
use AsyncHttp\Http\MultiAsyncHandler;

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