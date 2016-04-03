<?php

namespace app\model;

use app\config\DatabaseHelper;

class Categorie
{
    public $id;
    public $nom;

    public function __construct($id)
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT * FROM categories WHERE id_categorie = '.$id);
        $data = $bdd->execute();

        $this->nom = $data[0]['nom'];
        $this->id = $id;
    }
}
