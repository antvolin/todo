<?php

namespace BeeJeeMVC\Lib;

class SecretGenerator
{
    /**
     * @return string
     */
    public function generateSecret(): string
    {
        return md5($_ENV['TOKEN_SECRET'].uniqid('secret', true));
    }
}
