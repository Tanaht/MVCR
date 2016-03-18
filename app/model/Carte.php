<?php

namespace app\model;

class Carte {

	public $id;
	public $nom;
	public $niveau;
	public $attaque;
	public $defense;
	public $description;
	public $attribut;
	public $types;
	public $effet;
	public $categorie;

	public function __construct($id) {
		$bdd = DatabaseHelper::getBdd();

		$bdd->query("SELECT * FROM cartes WHERE id_carte = " . $id);
		$data = $bdd->execute();
		$this->types = array();


		$this->id = $data[0]["id_carte"];
		
		$this->nom = $data[0]["nom"];
		
		if($data[0]["attaque"] != null)
			$this->attaque = $data[0]["attaque"];
		
		if($data[0]["defense"] != null)
			$this->defense = $data[0]["defense"];
		
		$this->description = $data[0]["description"];		
		
		if($data[0]["niveau"] != null)
			$this->niveau = $data[0]["niveau"];
		
		$this->attribut = new Attribut($data[0]["id_attribut"]);
		
		if($data[0]["id_categorie"] != null)
			$this->categorie = new categorie($data[0]["id_categorie"]);

		if($data[0]["id_effet"] != null)
			$this->effet = new Effet($data[0]["id_effet"]);

		$bdd->query("SELECT id_type FROM carte_types");

		$bdd->forEachRow(array($this, "constructTypes"));
	}

	public function constructTypes(&$row) {
		array_push($this->types, new Type($row["id_type"]));
	}
}