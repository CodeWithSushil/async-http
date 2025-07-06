<?php
namespace AsyncHttp\Http;

class MultiAsyncHandler
{
    private array $streams = [];
    private array $callbacks = [];

    public function add(callable $generatorCallback, callable $onDone): void {
        $gen = $generatorCallback();
        $gen->rewind();
        $socket = $this->extractSocket($gen);
        if ($socket) {
            $this->streams[(int)$socket] = $socket;
            $this->callbacks[(int)$socket] = [$gen, $onDone];
        }
    }

    public function run(): void {
        while ($this->streams) {
            $read = array_values($this->streams);
            $write = null; $except = null;
            stream_select($read, $write, $except, 5);

            foreach ($read as $socket) {
                $id = (int)$socket;
                [$gen, $callback] = $this->callbacks[$id];
                if ($gen->valid()) {
                    $response = $gen->current();
                    $callback($response);
                    unset($this->streams[$id], $this->callbacks[$id]);
                }
            }
        }
    }

    private function extractSocket(\Generator $gen): mixed {
        $r = new \ReflectionObject($gen);
        foreach ($r->getProperties() as $prop) {
            $prop->setAccessible(true);
            $val = $prop->getValue($gen);
            if (is_resource($val) && get_resource_type($val) === 'stream') {
                return $val;
            }
        }
        return null;
    }
}