<?php

namespace Todo\Lib\DB;

interface DatabaseConnectionInterface
{
    public function __construct(DatabaseConfigurationInterface $config);

    public function getDsn(): string;
}
