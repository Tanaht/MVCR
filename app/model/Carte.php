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
	public $utilisateur;
	public $dateCreation;

	public function __construct($id) {
		$bdd = DatabaseHelper::getBdd();

		$bdd->query("SELECT * FROM cartes WHERE id_carte = " . $id);
		
		$data = $bdd->execute();
		
		if(!$data)
			return;

		$this->types = array();


		$this->id = $data[0]["id_carte"];
		
		$this->nom = $data[0]["nom"];

		$this->utilisateur = new Utilisateur($data[0]["id_utilisateur"]);
		
		$this->attaque = $data[0]["attaque"];
		$this->defense = $data[0]["defense"];
		$this->dateCreation = $data[0]["dateCreation"];
		
		$this->description = $data[0]["description"];
		
		if($data[0]["niveau"] != null)
			$this->niveau = $data[0]["niveau"];
		else
			$this->niveau = "";


		$this->attribut = new Attribut($data[0]["id_attribut"]);
		$this->categorie = new Categorie($data[0]["id_categorie"]);

		$this->effet = new Effet($data[0]["id_effet"]);

		$bdd->query("SELECT id_type FROM carte_types where id_carte=" . $this->id);

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

	public function update($nom, $niveau, $attaque, $defense, $categorie, $effet, $attribut, $types, $description) {
		$bdd = DatabaseHelper::getBdd();
		$bdd->prepare('UPDATE cartes '
			. ' SET nom=:nom,'
			. ' niveau=:niveau,'
			. ' attaque=:attaque,'
			. ' defense=:defense,'
			. ' id_categorie=:categorie,'
			. ' id_effet=:effet,'
			. ' id_attribut=:attribut,'
			. ' description=:description'
			. ' WHERE id_carte=' . $this->id);

		$bdd->bindParam(":nom", $nom, \PDO::PARAM_STR);
		$bdd->bindParam(":niveau", $niveau, \PDO::PARAM_INT);
		$bdd->bindParam(":attaque", $attaque, \PDO::PARAM_STR);
		$bdd->bindParam(":defense", $defense, \PDO::PARAM_STR);
		$bdd->bindParam(":categorie", $categorie, \PDO::PARAM_INT);
		$bdd->bindParam(":effet", $effet, \PDO::PARAM_INT);
		$bdd->bindParam(":attribut", $attribut, \PDO::PARAM_INT);
		$bdd->bindParam(":description", $description, \PDO::PARAM_STR);

		if(!$bdd->execute(false)) {
			return false;
		}

		$bdd->query("DELETE FROM carte_types WHERE id_carte = " . $this->id);
		
		if(!$bdd->execute(true, true)) {
			return false;
		}

		$bdd->prepare("INSERT INTO carte_types(id_carte, id_type) VALUES(:id_carte, :id_type)");
		$bdd->bindParam(":id_carte", $this->id);
		
		foreach ($types as $key => $value) {
			$bdd->bindParam(":id_type", $value);
			if(!$bdd->execute(false))
				return false;
		}

		return true;
	}

	public function delete() {
		if($this->id == null)
			return false;

		$bdd = DatabaseHelper::getBdd();

		$bdd->query("DELETE FROM cartes WHERE id_carte = " . $this->id);
		return $bdd->execute(true, true);
	}
}