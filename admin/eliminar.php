<?php
// =====================================================================
// admin/eliminar.php  |  Lógica de eliminación (DELETE)
// Solo acepta POST. No muestra nada: procesa y redirige al listado.
// =====================================================================
require_once '../config/conexion.php';
require_once '../config/funciones.php';
$raizUrl = '../';
require_once '../config/auth_guard.php';

// Solo admin y empresa pueden usar el panel de productos
requerirRol(['admin', 'empresa'], $raizUrl);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$idProducto = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($idProducto === false || $idProducto === null || $idProducto < 1) {
    header('Location: index.php?status=notfound');
    exit;
}

try {
    // Baja con consulta preparada (las variantes del producto se borran en cascada)
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id_producto = :id");
    $stmt->execute([':id' => $idProducto]);

    if ($stmt->rowCount() === 0) {
        header('Location: index.php?status=notfound');
        exit;
    }

    header('Location: index.php?status=deleted');
    exit;
} catch (PDOException $ex) {
    error_log("Error al eliminar producto: " . $ex->getMessage());
    header('Location: index.php?status=error');
    exit;
}
