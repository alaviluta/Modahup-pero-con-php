<?php
// =====================================================================
// admin/editar.php  |  Formulario + Actualización (UPDATE)
// =====================================================================
require_once '../config/conexion.php';
require_once '../config/funciones.php';
$raizUrl = '../';
require_once '../config/auth_guard.php';

// Solo admin y empresa pueden usar el panel de productos
requerirRol(['admin', 'empresa'], $raizUrl);

$pageTitle = "Editar Producto";
$pageDescription = "Formulario para modificar los datos de un producto del catálogo.";
$baseHref = '../';

$errores = [];
$errorGeneral = '';

// El id llega por GET al abrir la página y por POST (campo oculto) al guardar
$tipoId = ($_SERVER['REQUEST_METHOD'] === 'POST') ? INPUT_POST : INPUT_GET;
$idProducto = filter_input($tipoId, 'id', FILTER_VALIDATE_INT);

if ($idProducto === false || $idProducto === null || $idProducto < 1) {
    header('Location: index.php?status=notfound');
    exit;
}

try {
    // Listas para los desplegables
    $stmt = $pdo->prepare("SELECT id_marca, nombre FROM marcas ORDER BY nombre ASC");
    $stmt->execute();
    $marcas = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC");
    $stmt->execute();
    $categorias = $stmt->fetchAll();

    // Producto actual (consulta preparada)
    $stmt = $pdo->prepare("SELECT id_producto, nombre, descripcion, precio, id_marca, id_categoria
                           FROM productos
                           WHERE id_producto = :id");
    $stmt->execute([':id' => $idProducto]);
    $producto = $stmt->fetch();
} catch (PDOException $ex) {
    error_log("Error al cargar producto: " . $ex->getMessage());
    die("No se pudieron cargar los datos del producto.");
}

if (!$producto) {
    header('Location: index.php?status=notfound');
    exit;
}

// Valores iniciales del formulario: los que están guardados en la base
$datos = [
    'nombre'       => $producto['nombre'],
    'descripcion'  => (string)$producto['descripcion'],
    'precio'       => $producto['precio'],
    'id_marca'     => $producto['id_marca'],
    'id_categoria' => $producto['id_categoria'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Captura y sanitización (si hay error se conserva lo que escribió el usuario)
    $datos['nombre']       = capturar(INPUT_POST, 'nombre');
    $datos['descripcion']  = capturar(INPUT_POST, 'descripcion');
    $datos['precio']       = capturar(INPUT_POST, 'precio');
    $datos['id_marca']     = capturar(INPUT_POST, 'id_marca');
    $datos['id_categoria'] = capturar(INPUT_POST, 'id_categoria');

    // 2. Validación en servidor
    $errores = validarProducto($datos, $marcas, $categorias);

    // 3. Actualización con consulta preparada
    if (empty($errores)) {
        try {
            $sql = "UPDATE productos
                    SET nombre = :nombre,
                        descripcion = :descripcion,
                        precio = :precio,
                        id_marca = :id_marca,
                        id_categoria = :id_categoria
                    WHERE id_producto = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre'       => $datos['nombre'],
                ':descripcion'  => $datos['descripcion'],
                ':precio'       => str_replace(',', '.', $datos['precio']),
                ':id_marca'     => (int)$datos['id_marca'],
                ':id_categoria' => (int)$datos['id_categoria'],
                ':id'           => $idProducto,
            ]);

            header('Location: index.php?status=updated');
            exit;
        } catch (PDOException $ex) {
            error_log("Error al actualizar producto: " . $ex->getMessage());
            $errorGeneral = "No se pudo actualizar el producto. Intentá nuevamente.";
        }
    }
}

// Datos para el formulario compartido
$formAction = 'admin/editar.php';
$textoBoton = 'Guardar cambios';

include '../includes/header.php';
?>

        <div class="header-seccion">
            <h1>Editar Producto</h1>
            <p>Modificá los datos del producto y guardá los cambios.</p>
        </div>

<?php
include '_formulario.php';
include '../includes/footer.php';
?>
