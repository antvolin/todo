<?php

namespace Todo\Model;

class Id
{
    private $value;

    public function __construct($value = null)
    {
        if ($value) {
            $this->value = $value;
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }
}
