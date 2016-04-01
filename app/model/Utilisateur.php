<?php

namespace app\model;

class Utilisateur
{
    public $id;
    public $username;
    public $email;
    public $sudo;

    public function __construct($id)
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_utilisateur, username, email, sudo FROM utilisateurs WHERE id_utilisateur = '.$id);
        $data = $bdd->execute();

        $this->username = $data[0]['username'];
        $this->email = $data[0]['email'];
        $this->sudo = $data[0]['sudo'];
        $this->id = $id;
    }
}
