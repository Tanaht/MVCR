<?php

namespace app\model;

class Carte {

	public $id_carte;
	public $nom;
	public $niveau;
	public $attaque;
	public $defense;
	public $description;
	public $id_attribut;
	public $id_type;
	public $id_categorie;

	public function __construct($id) {
		$bdd = DatabaseHelper::getBdd();

		$bdd->query("SELECT * FROM cartes WHERE id_carte = " . $id);
		$data = $bdd->execute();


		$this->id_carte = $data[0]["id_carte"];
		$this->nom = $data[0]["nom"];
		$this->attaque = $data[0]["attaque"];
		$this->defense = $data[0]["defense"];
		$this->description = $data[0]["description"];		
		$this->niveau = $data[0]["niveau"];
		$this->id_attribut = $data[0]["id_attribut"];
		$this->id_type = $data[0]["id_type"];
		$this->id_categorie = $data[0]["id_categorie"];
	}
}