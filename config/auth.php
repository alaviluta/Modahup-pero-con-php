<?php
// =====================================================================
// config/auth.php  |  Manejo de sesión y permisos
// =====================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Devuelve los datos del usuario logueado (o null si no hay sesión)
function usuarioActual() {
    return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
}

// Guarda un mensaje para mostrarlo una sola vez en la próxima página
function setFlash($tipo, $texto) {
    $_SESSION['flash'] = ['tipo' => $tipo, 'texto' => $texto];
}

// Protege una página del panel: exige sesión iniciada y uno de los roles permitidos.
// Se usa desde las páginas de la carpeta admin/ (por eso las rutas con ../)
function requerirRol($rolesPermitidos) {
    $usuario = usuarioActual();

    if ($usuario === null) {
        setFlash('error', 'Iniciá sesión para acceder al panel.');
        header('Location: ../iniciodesesion.php');
        exit;
    }

    if (!in_array($usuario['rol'], $rolesPermitidos, true)) {
        setFlash('error', 'Tu cuenta no tiene permisos para acceder al panel.');
        header('Location: ../index.php');
        exit;
    }
}
