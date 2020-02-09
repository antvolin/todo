<?php

namespace Todo\Lib\Service\DB;

class MemcachedDBConfiguration
{
    private string $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function getHost(): string
    {
        return $this->host;
    }
}
