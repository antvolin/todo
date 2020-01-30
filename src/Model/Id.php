<?php

namespace Todo\Model;

class Id
{
    private ?int $value = null;

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
     * @return string|int|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string|int|null $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
