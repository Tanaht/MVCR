<?php

namespace app\router;

use app\view\View;
use app\controller\Controller;
use app\model\User;

class RouterV2 {
	public function run() {
        echo $_SERVER["PATH_INFO"];
    }
}