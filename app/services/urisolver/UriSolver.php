<?php

namespace urisolver;

class UriSolver {

	
	private $_uriParsed;//URL
	private $_recognizeRoute;//ROUTE
	private $_recognizeRouteParams;

	public function __construct($uri) {
		$this->_uriParsed = UriParser::parse($uri);
	}

	public function matche($uriRoute) {
		$this->_recognizeRouteParams = array();

		$uriRouteParsed = UriParser::parse($uriRoute);

		if(!self::matcheSize($uriRouteParsed))
			return false;

		for($i = 0 ; $i < count($uriRouteParsed) ; $i++) {
			if(!self::matchePiece($this->_uriParsed[$i], $uriRouteParsed[$i]))
				return false;
		}

		$this->_recognizeRoute = $uriRoute;
		return true;
	}

	public function getMatchedRouteUri() {
		return $this->_recognizeRoute;
	}

	public function getMatchedRouteParams() {
		return $this->_recognizeRouteParams;
	}

	private function matchePiece($urlPieceContainer, $urlRoutePieceContainer) {
		if($urlRoutePieceContainer['type'] != UriParser::variableParam)
			if($urlPieceContainer['piece'] == $urlRoutePieceContainer['piece'])
				return true;
			else
				return false;


		if($urlRoutePieceContainer['variable']['type'] == $urlPieceContainer['type']) {
			$this->_recognizeRouteParams[$urlRoutePieceContainer['variable']['name']] = $urlPieceContainer['piece'];
			return true;
		}

		return false;
	}

	private function matcheSize($uriRouteParsed) {
		return count($uriRouteParsed) == count($this->_uriParsed);
	}
}