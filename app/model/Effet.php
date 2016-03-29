<?php

namespace app\model;

class Effet {
	
	public $id;
	public $nom;

	public function __construct($id) {
		$bdd = DatabaseHelper::getBdd();

		$bdd->query("SELECT * FROM effets WHERE id_effet = " . $id);
		$data = $bdd->execute();

		$this->nom = $data[0]["nom"];
		$this->id = $data[0]["id_effet"];
	}
}