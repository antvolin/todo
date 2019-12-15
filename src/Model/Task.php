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
     * @var string
     */
    private $hash;

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
        $this->hash = (new HashGenerator())->generateHash($this->userName, $this->email, $this->text);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->hash;
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
        return $this->editStatus.$this->doneStatus;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function edit(string $text): self
    {
        $this->text = new Text($text);
        $this->editStatus = new Status(AllowedStatuses::EDITED_STATUS);

        return $this;
    }

    /**
     * @return $this
     */
    public function done(): self
    {
        $this->doneStatus = new Status(AllowedStatuses::DONE_STATUS);

        return $this;
    }
}
