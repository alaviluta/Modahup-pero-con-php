<?php
// =====================================================================
// clase7/crear_admin.php  |  Se ejecuta UNA VEZ para poder entrar como administrador
// Le pone una contraseña real (con hash) al usuario admin@modahup.com del TP6.
// Solo funciona en entorno local y en modo "development".
// BORRAR ESTE ARCHIVO después de usarlo.
// =====================================================================
require_once '../config/conexion.php';

$esLocal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);

if ($env['env'] !== 'development' || !$esLocal) {
    http_response_code(403);
    die("Acceso denegado.");
}

$emailAdmin = 'admin@modahup.com';
$passwordAdmin = 'Admin1234';

try {
    $hash = password_hash($passwordAdmin, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE usuarios SET password = :password WHERE email = :email");
    $stmt->execute([':password' => $hash, ':email' => $emailAdmin]);

    if ($stmt->rowCount() > 0) {
        $mensaje = "✅ Listo. Ya podés iniciar sesión con <strong>" . htmlspecialchars($emailAdmin, ENT_QUOTES, 'UTF-8')
                 . "</strong> y la contraseña <strong>" . htmlspecialchars($passwordAdmin, ENT_QUOTES, 'UTF-8')
                 . "</strong>. Borrá este archivo (clase7/crear_admin.php).";
    } else {
        $mensaje = "⚠️ No se modificó ninguna fila. Puede que ya tenga esa contraseña o que el usuario admin no exista (¿ejecutaste consultas.sql del TP6?).";
    }
} catch (PDOException $ex) {
    error_log("Error en crear_admin: " . $ex->getMessage());
    $mensaje = "❌ Error: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear administrador</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 0 15px;">
    <h1>Crear administrador</h1>
    <p><?php echo $mensaje; ?></p>
</body>
</html>
