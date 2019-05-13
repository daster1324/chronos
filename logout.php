<?php

session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

switch ($_GET['redirect']) {
    case 'gestion':
    case 'docentes':
    $destino = $_GET['redirect'];
        break;
    
    default:
    $destino = "";
        break;
}
header("Location: /".$destino);