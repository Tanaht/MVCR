<?php

namespace formBuilder;

class Input {
	const TEXTAREA = 0;
	const INPUT_FILE = 1;
	const INPUT_TEXT = 2;
	const RADIO_BUTTONS = 3;
	const CHECKBOX_BUTTONS = 4;
	const LIST_ITEMS = 5;
    const BUTTON_INPUT = 6;

    const CONDITION_REQ = "required";

	private $input_type;
	private $name;
	private $values;
    private $inputValue;
    private $conditions;

	public function __construct($type, $name, $values=null) {
		$this->input_type = $type;
		$this->name = $name;
        $this->values = $values;
        $this->inputValue = null;
        $this->conditions = array();
	}

    public function setInputValue($value) {
        $this->inputValue = $value;
        if($this->inputValue == null)
            $this->inputValue = "";
        return $this;
    }

    public function getInputValue() {
        return $this->inputValue;
    }

    public function getName() {
        return $this->name;
    }

    public function setConditions(array $conditions) {
        $this->conditions = $conditions;
        return $this;
    }

    public function isValid() {
        switch ($this->input_type) {
            case self::TEXTAREA:
                return $this->inputValue != null;
                break;
            case self::INPUT_FILE:
                break;
            case self::INPUT_TEXT:
                return $this->inputValue != null;
                break;
            case self::RADIO_BUTTONS:
                return in_array($this->inputValue, $this->values);
                break;
            case self::CHECKBOX_BUTTONS:
                foreach ($this->inputValue as $checkBoxChecked) {
                    if(!in_array($checkBoxChecked, $this->values))
                        return false;
                    return true;
                }
                break;
            case self::LIST_ITEMS:
                return in_array($this->inputValue, $this->values);
                break;
            case self::BUTTON_INPUT:
                return true;
                break;
        }
    }


    
}