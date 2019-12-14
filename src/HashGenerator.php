<?php

namespace BeeJeeMVC;

class HashGenerator
{
    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     *
     * @return string
     */
    public function generateHash(string $userName, string $email, string $text): string
    {
        return hash('md5', $userName.$email.$text);
    }
}
