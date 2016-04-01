<?php

namespace app\model;

class Type
{
    public $id;
    public $nom;

    public function __construct($id)
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT * FROM types WHERE id_type = '.$id);
        $data = $bdd->execute();

        $this->nom = $data[0]['nom'];
        $this->id = $id;
    }
}
