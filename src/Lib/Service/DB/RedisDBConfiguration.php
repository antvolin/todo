<?php

namespace Todo\Lib\Service\DB;

class RedisDBConfiguration
{
    private string $host;
    private string $port;
    private string $password;

    public function __construct(string $host, string $port, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
