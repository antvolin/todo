<?php

namespace Todo\Lib\Service\Secret;

class SecretGeneratorService implements SecretGeneratorServiceInterface
{
    /**
     * @var string
     */
    private $tokenSecretPrefix;

    /**
     * @var string
     */
    private $tokenSecret;

    /**
     * @inheritDoc
     */
    public function __construct(string $tokenSecretPrefix, string $tokenSecret)
    {
        $this->tokenSecretPrefix = $tokenSecretPrefix;
        $this->tokenSecret = $tokenSecret;
    }

    /**
     * @inheritDoc
     */
    public function generateSecret($secretPrefix = null): string
    {
        if (!$secretPrefix) {
            $secretPrefix = uniqid($this->tokenSecretPrefix, true);
        }

        return md5($this->tokenSecret.$secretPrefix);
    }
}
