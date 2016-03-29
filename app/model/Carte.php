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
		else
			$this->attaque = "";
		
		if($data[0]["defense"] != null)
			$this->defense = $data[0]["defense"];
		else
			$this->defense = "";
		
		$this->description = $data[0]["description"];		
		
		if($data[0]["niveau"] != null)
			$this->niveau = $data[0]["niveau"];
		else
			$this->niveau = "";
		
		$this->attribut = new Attribut($data[0]["id_attribut"]);
		
		if($data[0]["id_categorie"] != null)
			$this->categorie = new categorie($data[0]["id_categorie"]);
		else
			$this->categorie = "";

		if($data[0]["id_effet"] != null)
			$this->effet = new Effet($data[0]["id_effet"]);
		else
			$this->effet = "";

		$bdd->query("SELECT id_type FROM carte_types");

		$bdd->forEachRow(array($this, "constructTypes"));
	}

	public function constructTypes(&$row) {
		array_push($this->types, new Type($row["id_type"]));
	}

	public static function create($id_utilisateur, $nom, $niveau, $attaque, $defense, $categorie, $effet, $attribut, $types, $description) {
		$bdd = DatabaseHelper::getBdd();
		$bdd->prepare("INSERT INTO cartes(id_utilisateur, id_attribut, id_categorie, id_effet, nom, niveau, attaque, defense, description) VALUES(:id_utilisateur, :attribut, :categorie, :effet, :nom, :niveau, :attaque, :defense, :description)");

		$bdd->bindParam(":id_utilisateur", $id_utilisateur);
		$bdd->bindParam(":nom", $nom);
		$bdd->bindParam(":niveau", $niveau);
		$bdd->bindParam(":attaque", $attaque);
		$bdd->bindParam(":defense", $defense);
		$bdd->bindParam(":categorie", $categorie);
		$bdd->bindParam(":effet", $effet);
		$bdd->bindParam(":attribut", $attribut);
		$bdd->bindParam(":description", $description);

		if(!$bdd->execute(false)) {
			return false;
		}

		$id_carte = $bdd->getId("id_carte")["id"];

		$bdd->prepare("INSERT INTO carte_types(id_carte, id_type) VALUES(:id_carte, :id_type)");
		$bdd->bindParam(":id_carte", $id_carte);
		
		foreach ($types as $key => $value) {
			$bdd->bindParam(":id_type", $value);
			if(!$bdd->execute(false))
				return false;
		}

		return $id_carte;
	}
}