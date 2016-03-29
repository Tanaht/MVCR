<?php

namespace app\util;

class InputFilter extends Filter {

	public function __construct() {
		parent::__construct("input");
	}
	
	/**
		$before doit être une array qui contient plusieurs élements de cette sorte: array("key" => "", "value" => "")
	*/
	public function filter($before, array $args = null){
		$html = "";
		$type = $args[0];
		$name = $args[1];
		$option = "";

		if(count($args) > 2) {
			$option = $args[2];
		}

		switch($type) {
			case "radio":
			case "checkbox":

				foreach ($before as $key => $value) {
					$selected = "";
					if($value["selected"] == true)
						$selected = "checked";
					$html .= '<label><input name="' . $name . '" type="' . $type . '"  value="' . $value["key"]  . '" ' . $option . ' > ' . $value["value"] . '</label>';
				}
				break;
			case "select":

				$html .= '<select id="' . $name . '" name="' . $name . '" ' . $option . '>';
				$html .= '<option value="">Choisissez un élément de la liste</option>';
				foreach ($before as $key => $value) {
					$selected = "";
					if($value["selected"] == true)
						$selected = "selected";

					$html .= '<option value="' . $value["key"] . '"  ' . $selected . ' >' . $value["value"] . '</option>';
				}

				$html .= '</select>';
				break;
			default:
				$html .= "<span>InputFilter : " . $type . " unknown</span>";
		}
		return $html;
	}
}