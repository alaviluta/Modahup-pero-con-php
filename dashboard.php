<?php
$pageTitle = "Mi Panel";
$pageDescription = "Panel del usuario con los datos de la cuenta y los accesos disponibles según su rol.";

// Página protegida: solo usuarios con sesión iniciada
require_once 'config/auth_guard.php';
require_once 'config/conexion.php';
require_once 'config/funciones.php';

$cuenta = null;
$errorGeneral = '';

try {
    $sql = "SELECT u.nombre, u.apellido, u.email, u.telefono, u.empresa, u.created_at, r.nombre AS rol
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['usuario_id']]);
    $cuenta = $stmt->fetch();
} catch (PDOException $ex) {
    error_log("Error en dashboard: " . $ex->getMessage());
    $errorGeneral = "No se pudieron cargar los datos de tu cuenta.";
}

$rol = $_SESSION['rol'];

include 'includes/header.php';
?>

        <div class="header-seccion">
            <h1>Mi Panel</h1>
            <p>Hola, <strong><?php echo e($_SESSION['usuario_nombre']); ?></strong>. Tu rol es <strong><?php echo e($rol); ?></strong>.</p>
        </div>

        <?php if ($errorGeneral !== ''): ?>
            <div class="mensaje-flash flash-error">❌ <?php echo e($errorGeneral); ?></div>
        <?php endif; ?>

        <?php if ($cuenta): ?>
            <div class="bloque-producto">
                <h2>👤 Datos de tu cuenta</h2>
                <p><strong>Nombre:</strong> <?php echo e($cuenta['nombre'] . ' ' . $cuenta['apellido']); ?></p>
                <p><strong>Correo:</strong> <?php echo e($cuenta['email']); ?></p>
                <?php if (!empty($cuenta['telefono'])): ?>
                    <p><strong>Teléfono:</strong> <?php echo e($cuenta['telefono']); ?></p>
                <?php endif; ?>
                <?php if (!empty($cuenta['empresa'])): ?>
                    <p><strong>Empresa:</strong> <?php echo e($cuenta['empresa']); ?></p>
                <?php endif; ?>
                <p><strong>Cuenta creada el:</strong> <?php echo e($cuenta['created_at']); ?></p>
            </div>
        <?php endif; ?>

        <div class="bloque-producto">
            <h2>🧭 Accesos disponibles</h2>

            <?php if ($rol === 'admin'): ?>
                <p>Como administrador podés ver los usuarios registrados y gestionar los productos del catálogo.</p>
                <a href="admin_panel.php" class="boton-link">👥 Panel de usuarios</a>
                <a href="admin/index.php" class="boton-link">⚙️ Gestión de productos</a>
            <?php elseif ($rol === 'empresa'): ?>
                <p>Como empresa podés gestionar los productos del catálogo: altas, modificaciones y bajas.</p>
                <a href="admin/index.php" class="boton-link">⚙️ Gestión de productos</a>
            <?php else: ?>
                <p>Con tu cuenta de usuario podés explorar los catálogos, buscar tiendas y enviarnos tus consultas.</p>
                <a href="clase5/tiendas.php" class="boton-link">🏬 Ver tiendas</a>
                <a href="clase5/buscar.php" class="boton-link">🔍 Buscar tiendas</a>
                <a href="clase5/contacto.php" class="boton-link">✉️ Contacto</a>
            <?php endif; ?>

            <a href="logout.php" class="boton-link" style="background-color: #6c757d;">Cerrar sesión</a>
        </div>

<?php include 'includes/footer.php'; ?>
