<?php

namespace app\model;

class Carte {
	public $nom;
	public $niveau;
	public $attaque;
	public $defense;
	public $description;
	public $attribut;
	public $types;
	public $categorie;

	public Carte __construct($id) {
		$bdd = DatabaseHelper::getBdd();

		$bdd->query("SELECT * FROM carte WHERE id_carte = " . $id);
		$data = $bdd->execute();

		$this->nom = $data["nom"];
		$this->attaque = $data["attaque"];
		$this->defense = $data["defense"];
		$this->description = $data["description"];		
		$this->niveau = $data["niveau"];
		$this->attribut = $data["attribut"];
		$this->types = $data["types"];
		$this->categorie = $data["categorie"];

		var_export($data);
	}
}