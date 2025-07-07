# Async HTTP Client for PHP

[![Packagist Version](https://img.shields.io/packagist/v/async-http/async-http.svg)](https://packagist.org/packages/async-http/async-httpi)
![Packagist Downloads](https://img.shields.io/packagist/dt/async-http/async-http?style=flat&logo=composer&color=blue)
[![Tests](https://github.com/CodeWithSushil/async-http/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/CodeWithSushil/async-http/actions/workflows/tests.yml)
[![CodeQL](https://github.com/CodeWithSushil/async-http/actions/workflows/github-code-scanning/codeql/badge.svg?branch=master)](https://github.com/CodeWithSushil/async-http/actions/workflows/github-code-scanning/codeql)


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

$url = 'https://jsonplaceholder.typicode.com/posts';

$client = new AsyncHttpClient();
foreach ($client->get($url) as $response) {
    echo $response->getBody();
}
```

