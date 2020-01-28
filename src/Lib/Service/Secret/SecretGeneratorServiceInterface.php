<?php

namespace Todo\Lib\Service\Secret;

interface SecretGeneratorServiceInterface
{
    /**
     * @param string $tokenSecretPrefix
     * @param string $tokenSecret
     */
    public function __construct(string $tokenSecretPrefix, string $tokenSecret);

    /**
     * @param null $secretPrefix
     *
     * @return string
     */
    public function generateSecret($secretPrefix = null): string;
}
