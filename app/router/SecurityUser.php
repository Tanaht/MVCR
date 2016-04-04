<?php

namespace app\router;

interface SecurityUser
{
    public function connected();
    public function authenticate($user, $password);
    public function getRole();
    public function logout();
    public function eraseCriticInformations();
    public function getUsers($username);
}
