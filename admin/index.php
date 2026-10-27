<?php
// =====================================================================
// admin/index.php  |  Listado principal de productos (READ)
// =====================================================================
require_once '../config/conexion.php';
require_once '../config/funciones.php';
$raizUrl = '../';
require_once '../config/auth_guard.php';

// Solo admin y empresa pueden usar el panel de productos
requerirRol(['admin', 'empresa'], $raizUrl);

$pageTitle = "Administración de Productos";
$pageDescription = "Panel para gestionar los productos del catálogo: alta, modificación y baja.";
$baseHref = '../'; // para que CSS, imágenes y menú se resuelvan desde la raíz del sitio

$registros = [];
$errorGeneral = '';

try {
    // Consulta preparada con INNER JOIN (productos + marcas + categorías)
    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio,
                   m.nombre AS marca, c.nombre AS categoria
            FROM productos p
            INNER JOIN marcas     m ON p.id_marca     = m.id_marca
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            ORDER BY p.id_producto DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $registros = $stmt->fetchAll();
} catch (PDOException $ex) {
    error_log("Error al listar productos: " . $ex->getMessage());
    $errorGeneral = "No se pudieron cargar los productos.";
}

// Mensajes según el resultado de la última operación (lista cerrada: no se imprime nada del usuario)
$mensajes = [
    'created'  => '✅ Producto creado correctamente.',
    'updated'  => '✅ Producto actualizado correctamente.',
    'deleted'  => '✅ Producto eliminado correctamente.',
    'notfound' => '❌ El producto solicitado no existe.',
    'error'    => '❌ Ocurrió un error al procesar la operación.',
];
$status = capturar(INPUT_GET, 'status');
$mensajeEstado = isset($mensajes[$status]) ? $mensajes[$status] : '';
$esError = in_array($status, ['notfound', 'error'], true);

include '../includes/header.php';
?>

        <style>
            .tabla-admin {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }
            .tabla-admin th,
            .tabla-admin td {
                border: 1px solid var(--border-color);
                padding: 8px 10px;
                text-align: left;
                vertical-align: middle;
            }
            .tabla-admin th {
                background-color: var(--nav-bg);
                color: var(--nav-text);
            }
            .tabla-scroll {
                overflow-x: auto;
            }
        </style>

        <div class="header-seccion">
            <h1>Administración de Productos</h1>
            <p>Gestioná el catálogo: agregá, modificá o eliminá productos. Los cambios se guardan directamente en la base de datos.</p>
        </div>

        <?php if ($mensajeEstado !== ''): ?>
            <div style="<?php echo $esError
                ? 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'
                : 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?> padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo e($mensajeEstado); ?>
            </div>
        <?php endif; ?>

        <?php if ($errorGeneral !== ''): ?>
            <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                ❌ <?php echo e($errorGeneral); ?>
            </div>
        <?php endif; ?>

        <a href="admin/crear.php" class="boton-link">➕ Agregar nuevo producto</a>

        <div class="tabla-scroll">
            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($registros)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay productos cargados.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($registros as $item): ?>
                        <tr>
                            <td><?php echo e($item['id_producto']); ?></td>
                            <td><?php echo e($item['nombre']); ?></td>
                            <td><?php echo e($item['descripcion']); ?></td>
                            <td><?php echo e($item['marca']); ?></td>
                            <td><?php echo e($item['categoria']); ?></td>
                            <td>$<?php echo e(number_format((float)$item['precio'], 2, ',', '.')); ?></td>
                            <td>
                                <a href="admin/editar.php?id=<?php echo (int)$item['id_producto']; ?>" class="boton-link" style="background-color: #e0a800; margin-top: 0;">Editar</a>

                                <!-- La baja se envía por POST (no por un link GET) para evitar borrados accidentales -->
                                <form action="admin/eliminar.php" method="POST" style="display: inline;" onsubmit="return confirm('¿Confirma eliminar este producto?');">
                                    <input type="hidden" name="id" value="<?php echo (int)$item['id_producto']; ?>">
                                    <button type="submit" class="boton-link" style="background-color: #dc3545; cursor: pointer; margin-top: 0;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

<?php include '../includes/footer.php'; ?>
