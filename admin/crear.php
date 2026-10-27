<?php
// =====================================================================
// admin/crear.php  |  Formulario + Inserción (CREATE)
// =====================================================================
require_once '../config/conexion.php';
require_once '../config/funciones.php';
$raizUrl = '../';
require_once '../config/auth_guard.php';

// Solo admin y empresa pueden usar el panel de productos
requerirRol(['admin', 'empresa'], $raizUrl);

$pageTitle = "Agregar Producto";
$pageDescription = "Formulario para dar de alta un nuevo producto en el catálogo.";
$baseHref = '../';

$errores = [];
$errorGeneral = '';
$datos = [
    'nombre'       => '',
    'descripcion'  => '',
    'precio'       => '',
    'id_marca'     => '',
    'id_categoria' => '',
];

try {
    // Listas para los desplegables (consultas preparadas)
    $stmt = $pdo->prepare("SELECT id_marca, nombre FROM marcas ORDER BY nombre ASC");
    $stmt->execute();
    $marcas = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC");
    $stmt->execute();
    $categorias = $stmt->fetchAll();
} catch (PDOException $ex) {
    error_log("Error al cargar listas: " . $ex->getMessage());
    die("No se pudieron cargar los datos del formulario.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Captura y sanitización
    $datos['nombre']       = capturar(INPUT_POST, 'nombre');
    $datos['descripcion']  = capturar(INPUT_POST, 'descripcion');
    $datos['precio']       = capturar(INPUT_POST, 'precio');
    $datos['id_marca']     = capturar(INPUT_POST, 'id_marca');
    $datos['id_categoria'] = capturar(INPUT_POST, 'id_categoria');

    // 2. Validación en servidor
    $errores = validarProducto($datos, $marcas, $categorias);

    // 3. Inserción con consulta preparada
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO productos (nombre, descripcion, precio, id_marca, id_categoria)
                    VALUES (:nombre, :descripcion, :precio, :id_marca, :id_categoria)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre'       => $datos['nombre'],
                ':descripcion'  => $datos['descripcion'],
                ':precio'       => str_replace(',', '.', $datos['precio']),
                ':id_marca'     => (int)$datos['id_marca'],
                ':id_categoria' => (int)$datos['id_categoria'],
            ]);

            header('Location: index.php?status=created');
            exit;
        } catch (PDOException $ex) {
            error_log("Error al crear producto: " . $ex->getMessage());
            $errorGeneral = "No se pudo guardar el producto. Intentá nuevamente.";
        }
    }
}

// Datos para el formulario compartido
$formAction = 'admin/crear.php';
$textoBoton = 'Guardar producto';
$idProducto = null;

include '../includes/header.php';
?>

        <div class="header-seccion">
            <h1>Agregar Producto</h1>
            <p>Completá los datos del nuevo producto del catálogo.</p>
        </div>

<?php
include '_formulario.php';
include '../includes/footer.php';
?>
