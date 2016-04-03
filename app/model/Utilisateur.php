<?php

namespace app\model;

use app\config\DatabaseHelper;

class Utilisateur
{
    public $id;
    public $username;
    public $email;
    public $sudo;
    public $password;

    public function __construct($id)
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT * FROM utilisateurs WHERE id_utilisateur = '.$id);
        $data = $bdd->execute();

        $this->username = $data[0]['username'];
        $this->email = $data[0]['email'];
        $this->sudo = $data[0]['sudo'];
        $this->password = $data[0]['password'];
        $this->id = $id;
    }
}
