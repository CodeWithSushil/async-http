<?php

namespace Async\Http;

class Promise
{
    private \Fiber $fiber;

    public function __construct(callable $callback)
    {
        $this->fiber = new \Fiber($callback);
    }

    public function await()
    {
        return $this->fiber->start();
    }
}
