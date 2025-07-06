<?php
use PHPUnit\Framework\TestCase;
use AsyncHttp\Http\AsyncHttpClient;

class AsyncHttpClientTest extends TestCase
{
    public function testSimpleGetRequest()
    {
        $client = new AsyncHttpClient();
        foreach ($client->get('https://jsonplaceholder.typicode.com/posts/1') as $response) {
            $this->assertStringContainsString('userId', $response->getBody());
        }
    }
}