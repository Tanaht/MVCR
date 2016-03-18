<?php

namespace app\model;

class Effet {
	
	public $nom;

	public function __construct($id) {
		$bdd = DatabaseHelper::getBdd();

		$bdd->query("SELECT * FROM effets WHERE id_effet = " . $id);
		$data = $bdd->execute();

		$this->nom = $data[0]["nom"];
	}
}