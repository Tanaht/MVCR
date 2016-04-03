<?php

namespace app\router;

interface SecurityUser{
	function connected();
	function authenticate($user, $password);
	function getRole();
	function logout();
	function eraseCriticInformations();
	function getUsers($username);
}