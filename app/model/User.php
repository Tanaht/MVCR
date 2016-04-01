<?php

namespace app\model;

use app\services\PasswordCompat;

class User
{
    //status:
    const LOGON = 'LOGON';
    const LOGOFF = 'LOGOFF';

    //sudo(rights):
    const ADMIN = 'ADMIN';
    const MEMBER = 'MEMBER';
    const USER = 'USER';

    private $_id;

    public $_status;
    public $username;
    public $email;
    public $sudo;

    private $_registerPasswd;
    private $_tmpPASSWD;

    public function __construct()
    {
        session_start();//On met le session start ici en attendant une place plus logique...(peut-être dans index.php)

        $this->_status = self::LOGON;
        $this->sudo = self::USER;

        if (!isset($_SESSION['username'])) {
            $_SESSION['sudo'] = $this->sudo;

            return;
        }

        $this->_status = self::LOGOFF;
        $this->sudo = $_SESSION['sudo'];
        $this->username = $_SESSION['username'];
        $this->email = $_SESSION['email'];
        $this->_id = $_SESSION['id'];
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

    public function connect($user, $passwd)
    {
        if ($this->_status == self::LOGOFF && $this->username == $user) {
            return true;
        }

        $this->_tmpPASSWD = $passwd;

        $bdd = DatabaseHelper::getBdd();
        $bdd->query('SELECT id_utilisateur, username, password FROM utilisateurs WHERE username = "'.$user.'"');
        $bdd->forEachRow(array($this, 'verifyUser'));

        $this->_tmpPASSWD = '';

        if ($this->_status == self::LOGOFF) {
            $user = new Utilisateur($this->_id);

            $this->username = $user->username;
            $this->email = $user->email;
            $this->sudo = $user->sudo;

            $_SESSION['username'] = $this->username;
            $_SESSION['email'] = $this->email;
            $_SESSION['sudo'] = $this->sudo;
            $_SESSION['id'] = $this->_id;

            return true;
        }

        return false;
    }

    public function verifyUser(&$user)
    {
        if ($this->_status == self::LOGOFF) {
            return;
        }

        if (PasswordCompat::password_verify($this->_tmpPASSWD, $user['password'])) {
            $this->_status = self::LOGOFF;
            $this->_id = $user['id_utilisateur'];
        }
    }

    public function disconnect()
    {
        session_destroy();
        $this->_status = self::LOGON;
        $this->sudo = self::USER;
        $_SESSION['sudo'] = $this->sudo;
    }

    public function getId()
    {
        return $this->_id;
    }
}

/*
$hash = PasswordCompat::password_hash("toto", PASSWORD_BCRYPT);
PasswordCompat::password_verify("toto", $hash);

*/
