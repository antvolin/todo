<?php

namespace Todo\Model;

class Id
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param int|null $value
     */
    public function __construct(?int $value = null)
    {
        if ($value) {
            $this->value = $value;
        }
    }

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }
}
