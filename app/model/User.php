<?php

namespace app\model;

use app\config\DatabaseHelper;
use app\services\PasswordCompat;
use app\router\SecurityUser;

class User implements SecurityUser
{
    private $connected;
    private $role;
    public $utilisateur;

    public function __construct()
    {
        $this->role = 'USER';
        $this->connected = false;
    }

    public function connected()
    {
        return $this->connected;
    }

    public function authenticate($user, $password)
    {
        if (PasswordCompat::password_verify($password, $user->password)) {
            $this->connected = true;
            $this->utilisateur = $user;
            $this->role = $this->utilisateur->sudo;
            $this->utilisateur;

            return true;
        }

        return false;
    }

    public function eraseCriticInformations()
    {
        $this->utilisateur->password = '';
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getUsers($username)
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query("SELECT id_utilisateur FROM utilisateurs WHERE username like '".trim($username)."'");

        $users = array();
        foreach ($bdd->execute() as $row) {
            $users[] = new Utilisateur($row['id_utilisateur']);
        }

        return $users;
    }

    public function logout()
    {
        session_destroy();
        $this->connected = false;
        $this->role = 'USER';
        $this->utilisateur = null;
    }

    /**
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
