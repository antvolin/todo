<?php

namespace BeeJeeMVC\Lib\Manager;

class SecretGeneratorManager
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
     * @param string $tokenSecretPrefix
     * @param string $tokenSecret
     */
    public function __construct(string $tokenSecretPrefix, string $tokenSecret)
    {
        $this->tokenSecretPrefix = $tokenSecretPrefix;
        $this->tokenSecret = $tokenSecret;
    }

    /**
     * @param null $secretPrefix
     *
     * @return string
     */
    public function generateSecret($secretPrefix = null): string
    {
        if (!$secretPrefix) {
            $secretPrefix = uniqid($this->tokenSecretPrefix, true);
        }

        return md5($this->tokenSecret.$secretPrefix);
    }
}
