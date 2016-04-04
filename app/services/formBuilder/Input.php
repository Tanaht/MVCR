<?php

namespace formBuilder;

class Input
{
    private $name;
    private $value;
    private $isRequired;
    public function __construct($name)
    {
        $this->name = $name;
        $this->isRequired = false;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        if (is_array($value)) {
            $this->value = $value;
        } else {
            $this->value = trim($value);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function required()
    {
        $this->isRequired = true;
    }

    public function isValid()
    {
        if ($this->isRequired && !isset($this->value)) {
            return false;
        }

        return true;
    }
}
