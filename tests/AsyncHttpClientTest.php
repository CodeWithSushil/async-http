<?php

use PHPUnit\Framework\TestCase;

// use Async\Http\AsyncHttpClient;

class AsyncHttpClientTest extends TestCase
{
    public function test_simple_get_request()
    {
        $client = new AsyncHttpClient;
        foreach ($client->get('https://jsonplaceholder.typicode.com/posts/1') as $response) {
            $this->assertStringContainsString('userId', $response->getBody());
        }
    }
}
