<?php

namespace BeeJeeMVC\Lib;

class SecretGenerator
{
    /**
     * @param null $secretPrefix
     *
     * @return string
     */
    public function generateSecret($secretPrefix = null): string
    {
        if (!$secretPrefix) {
            $secretPrefix = uniqid($_ENV['TOKEN_SECRET_PREFIX'], true);
        }

        return md5($_ENV['TOKEN_SECRET'].$secretPrefix);
    }
}
