<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\IdGenerator;

class Task
{
    /**
     * @var UserName
     */
    private $userName;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var Text
     */
    private $text;

    /**
     * @var bool
     */
    private $edited = false;

    /**
     * @var bool
     */
    private $done = false;

    /**
     * @var string
     */
    private $id;

    /**
     * @param UserName $userName
     * @param Email $email
     * @param Text $text
     */
    public function __construct(UserName $userName, Email $email, Text $text)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->text = $text;
        $this->id = (new IdGenerator())->generateId($this->userName, $this->email, $this->text);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return UserName
     */
    public function getUserName(): UserName
    {
        return $this->userName;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Text
     */
    public function getText(): Text
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->edited;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }

    /**
     * @param string $text
     */
    public function edit(string $text): void
    {
        $this->text = new Text($text);
        $this->edited = true;
    }

    public function done(): void
    {
        $this->done = true;
    }
}
