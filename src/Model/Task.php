<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\AllowedStatuses;
use BeeJeeMVC\Lib\HashGenerator;

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
     * @var Status
     */
    private $editStatus;

    /**
     * @var Status
     */
    private $doneStatus;

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
     * @return string
     */
    public function __toString(): string
    {
        return $this->getHash();
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return (new HashGenerator())->generateHash($this->userName, $this->email, $this->text);
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->editStatus.' '.$this->doneStatus;
    }

    /**
     * @param string $text
     */
    public function edit(string $text): void
    {
        $this->text = new Text($text);
        $editStatus = (new AllowedStatuses())->getEditStatus();
        $this->editStatus = new Status($editStatus);
    }

    public function done(): void
    {
        $doneStatus = (new AllowedStatuses())->getDoneStatus();
        $this->doneStatus = new Status($doneStatus);
    }
}
