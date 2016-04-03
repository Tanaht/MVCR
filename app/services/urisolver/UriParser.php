<?php

namespace urisolver;

class UriParser {

	const intParam = "INT";
	const stringParam = "STRING";
	const emptyParam = "EMPTY";
	const variableParam = "VARIABLE";
	const variableParamRegex = "{\s*[a-zA-Z]+?\s*:\s*(" . self::intParam . "|" . self::stringParam . ")\s*}";


	public static function parse($uri) {
		$uriPieces = explode("/", $uri);
		foreach ($uriPieces as $key => $value) {
			if($value == "")
				unset($uriPieces[$key]);
		}
		$uriPieces = array_values($uriPieces);
		$uriPiecesType = self::retrieveUriPiecesTypes($uriPieces);
		self::generateUriPiecesArrayData($uriPieces, $uriPiecesType);

		return $uriPieces;
	}

	public static function getType($uriPiece) {
		if($uriPiece == "")
			return self::emptyParam;

		if(self::isVariable($uriPiece))
			return self::variableParam;

		if(filter_var($uriPiece, FILTER_VALIDATE_INT))
			return self::intParam;
		else
			return self::stringParam;
	}

	private static function isVariable($uriPiece) {
		return preg_match('/'.self::variableParamRegex.'/', $uriPiece) == 1;
	}

	private static function retrieveUriPiecesTypes($uriPieces) {
		$uriPiecesType = array();

		for($i=0 ; $i < count($uriPieces) ; $i++)
			$uriPiecesType[$uriPieces[$i]] = array('save_index' => $i, 'type' => self::getType($uriPieces[$i]));

		return $uriPiecesType;
	}

	private static function generateUriPiecesArrayData(&$uriPieces, $uriPiecesType) {
		foreach ($uriPiecesType as $piece => $arrayType) {
			$uriPieces[$arrayType['save_index']] = array(
				'piece' => $piece,
				'type' => $arrayType['type'],
			);
			if($arrayType['type'] == self::variableParam)
				self::addVariableInfo($uriPieces[$arrayType['save_index']]);
		}
	}

	private static function addVariableInfo(&$pieceContainer) {
		$piece = substr($pieceContainer['piece'], 1, strlen($pieceContainer['piece']) - 2);
		$pieceFragments = explode(":", $piece);

		$pieceContainer["variable"] = array("name" => trim($pieceFragments[0]), "type" => trim($pieceFragments[1]));

	}
}