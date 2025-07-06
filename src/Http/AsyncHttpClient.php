<?php

namespace Async\Http;

class AsyncHttpClient
{
    public function get(string $url, array $headers = []): \Generator
    {
        return $this->request('GET', $url, $headers);
    }

    public function post(string $url, array $headers = [], string $body = ''): \Generator
    {
        return $this->request('POST', $url, $headers, $body);
    }

    public function request(string $method, string $url, array $headers = [], string $body = ''): \Generator
    {
        $parts = parse_url($url);
        $scheme = $parts['scheme'] ?? 'http';
        $host = $parts['host'] ?? 'localhost';
        $port = $scheme === 'https' ? 443 : 80;
        $path = $parts['path'] ?? '/';
        $query = $parts['query'] ?? '';
        if ($query) {
            $path .= '?'.$query;
        }

        $remote = ($scheme === 'https' ? 'ssl://' : '').$host.':'.$port;
        $errno = $errstr = null;

        $fp = stream_socket_client($remote, $errno, $errstr, 5, STREAM_CLIENT_CONNECT | STREAM_CLIENT_ASYNC_CONNECT);
        if (! $fp) {
            throw new \Exception("Connection failed: $errstr");
        }

        stream_set_blocking($fp, false);

        // Build request headers
        $headerStr = "$method $path HTTP/1.1\r\n";
        $headerStr .= "Host: $host\r\n";
        $headerStr .= "Connection: close\r\n";

        foreach ($headers as $k => $v) {
            $headerStr .= "$k: $v\r\n";
        }

        if ($body !== '') {
            $headerStr .= 'Content-Length: '.strlen($body)."\r\n";
        }

        $headerStr .= "\r\n".$body;

        fwrite($fp, $headerStr);

        // Wait for response
        $read = [$fp];
        $write = null;
        $except = null;
        if (stream_select($read, $write, $except, 5)) {
            $response = '';
            while (! feof($fp)) {
                $chunk = fread($fp, 8192);
                if ($chunk === false) {
                    break;
                }
                $response .= $chunk;
            }
            fclose($fp);

            // Split headers and body
            [$rawHeaders, $body] = explode("\r\n\r\n", $response, 2);

            return yield new HttpResponse($rawHeaders, $body);
        } else {
            throw new \Exception('Timeout waiting for response');
        }
    }

    public function put(string $url, array $headers = [], string $body = ''): \Generator
    {
        return $this->request('PUT', $url, $headers, $body);
    }

    public function patch(string $url, array $headers = [], string $body = ''): \Generator
    {
        return $this->request('PATCH', $url, $headers, $body);
    }

    public function delete(string $url, array $headers = []): \Generator
    {
        return $this->request('DELETE', $url, $headers);
    }
}
