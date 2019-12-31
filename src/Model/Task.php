<?php

namespace BeeJeeMVC\Model;

class Task
{
    /**
     * @var int
     */
    private $id;

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
     * @param UserName $userName
     * @param Email $email
     * @param Text $text
     */
    public function __construct(UserName $userName, Email $email, Text $text)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
