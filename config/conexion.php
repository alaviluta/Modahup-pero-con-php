<?php
// =====================================================================
// config/conexion.php  |  Conexión segura a MySQL con PDO (TP7)
// Reutiliza los datos de env.php (host, base, usuario y contraseña).
// =====================================================================

$env = include __DIR__ . '/../env.php';

$host    = $env['db_host'];
$db      = $env['db_name'];     // tienda_ropa (la base creada en el TP6)
$user    = $env['db_user'];
$pass    = $env['db_pass'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // lanza excepciones ante errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // resultados como arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                    // consultas preparadas reales
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En desarrollo se muestra el detalle; en otro entorno solo un mensaje genérico
    if ($env['env'] === 'development') {
        die("Error crítico de conexión: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
    error_log("Error de conexión PDO: " . $e->getMessage());
    die("No se pudo conectar con la base de datos. Intentá nuevamente más tarde.");
}
