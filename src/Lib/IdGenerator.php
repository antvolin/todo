<?php

namespace BeeJeeMVC\Lib;

class IdGenerator
{
    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     *
     * @return string
     */
    public function generateId(string $userName, string $email, string $text): string
    {
        return hash('md5', $userName.$email.$text);
    }
}
