<?php

namespace Todo\Lib\Service\Secret;

interface SecretGeneratorServiceInterface
{
    public function __construct(string $tokenSecretPrefix, string $tokenSecret);

    public function generateSecret($secretPrefix = null): string;
}
