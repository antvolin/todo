<?php

namespace BeeJeeMVC;

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
    private $status;

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
        $createdStatus = (new AllowedStatuses())->getCreatedStatus();
        $this->status = new Status($createdStatus);
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
        return (new HashGenerator())->generateHash($this->userName->getValue(), $this->email->getValue(), $this->text->getValue());
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
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param string $text
     */
    public function edit(string $text): void
    {
        $this->text = new Text($text);
        $editStatus = (new AllowedStatuses())->getEditStatus();
        $this->status = new Status($editStatus);
    }

    public function done(): void
    {
        $doneStatus = (new AllowedStatuses())->getDoneStatus();
        $this->status = new Status($doneStatus);
    }
}
