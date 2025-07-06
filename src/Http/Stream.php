<?php

namespace Async\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    private string $contents;

    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    public function __toString(): string
    {
        return $this->contents;
    }

    public function close(): void {}

    public function detach()
    {
        return null;
    }

    public function getSize(): ?int
    {
        return strlen($this->contents);
    }

    public function tell(): int
    {
        return 0;
    }

    public function eof(): bool
    {
        return true;
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET): void {}

    public function rewind(): void {}

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): int
    {
        return 0;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        return substr($this->contents, 0, $length);
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getMetadata($key = null): mixed
    {
        return null;
    }
}
