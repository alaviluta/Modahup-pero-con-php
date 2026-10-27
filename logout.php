<?php
// =====================================================================
// logout.php  |  Cierra la sesión y vuelve al inicio
// =====================================================================
require_once 'config/auth.php';

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

session_destroy();

// Se abre una sesión nueva solo para dejar el mensaje de despedida
session_start();
setFlash('ok', '👋 Cerraste sesión correctamente.');

header('Location: index.php');
exit;
