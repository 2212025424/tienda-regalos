<?php

include_once 'app/cross/SessionManager.inc.php';
include_once 'app/cross/Redirect.inc.php';

SessionManager::stop_sesion();
Redirect::redirectTo(ROUTE_LOGIN);
?>
