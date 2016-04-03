<?php

namespace app\model;

use app\config\DatabaseHelper;

class Attribut
{
    public $nom;
    public $id;

    public function __construct($id)
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT * FROM attributs WHERE id_attribut = '.$id);
        $data = $bdd->execute();

        $this->nom = $data[0]['nom'];
        $this->id = $id;
    }
}
