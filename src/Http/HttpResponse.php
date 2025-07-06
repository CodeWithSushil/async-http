<?php

namespace Async\Http;

class HttpResponse
{
    public int $statusCode;

    public array $headers = [];

    public string $body;

    public function __construct(string $rawHeaders, string $body)
    {
        $this->body = $body;
        $lines = explode("\r\n", $rawHeaders);
        $statusLine = array_shift($lines);
        preg_match('#HTTP/\d\.\d\s+(\d+)#', $statusLine, $matches);
        $this->statusCode = (int) ($matches[1] ?? 0);

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                $this->headers[trim($key)] = trim($value);
            }
        }
    }

    public function getBody(): \Psr\Http\Message\StreamInterface
    {
        return new Stream($this->body);
    }

    public function json(): mixed
    {
        return json_decode($this->body, true);
    }

    public function text(): string
    {
        return $this->body;
    }
}
