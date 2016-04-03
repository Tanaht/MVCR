<?php

namespace app\model;

use app\config\DatabaseHelper;
use app\services\PasswordCompat;
use app\router\SecurityUser;

class User implements SecurityUser
{
    private $connected;
    private $role;
    private $utilisateur;

    public function __construct() {
        $this->role = 'USER';
        $this->connected = false;
    }

    public function connected() {
        return $this->connected;
    }

    public function authenticate($user, $password)
    {
        if (PasswordCompat::password_verify($user->password, $user['password'])) {
            $this->connected = true;
            $this->role = $this->utilisateur->sudo;
            $this->utilisateur = $user;
            $this->utilisateur;
            return true;
        }

        return false;
    }

    public function eraseCriticInformations() {
        $this->utilisateur->password = "";
    }

    public function getRole() {
        return $this->role;
    }

    public function getUsers($username) {
        $bbd = DatabaseHelper::getBdd();

        $bdd->query("SELECT id_utilisateur FROM Utilisateur WHERE username like '%". $username ."%'");

        $users = array();
        foreach ($bdd->execute() as $row) {
            $users[] = $row['id_utilisateur'];
        }
        return $users;
    }

    public function logout() {
        $this->connected = false;
        $this->role = 'USER';
    }


    /*
        $stmt = $dbh->prepare("INSERT INTO REGISTRY (name, value) VALUES (:name, :value)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':value', $value);
    */
    public function register($user, $passwd, $mail)
    {
        $bdd = DatabaseHelper::getBdd();
        $bdd->prepare('INSERT INTO utilisateurs(username, password, email) VALUES(:username, :password, :email)');

        $username = $user;
        $email = $mail;
        $password = PasswordCompat::password_hash($passwd, PASSWORD_BCRYPT);

        $bdd->bindParam(':username', $username);
        $bdd->bindParam(':password', $password);
        $bdd->bindParam(':email', $email);

        $bdd->execute(false);
    }
}

/*
$hash = PasswordCompat::password_hash("toto", PASSWORD_BCRYPT);
PasswordCompat::password_verify("toto", $hash);

*/
