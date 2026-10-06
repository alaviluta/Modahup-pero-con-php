<?php
$pageTitle = "Buscar Tiendas - Catálogo de Ropa Deportiva y Urbana";
$pageDescription = "Buscá marcas y tiendas de ropa deportiva y urbana por nombre o categoría.";

function e($valor) {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

// Captura segura: filter_input() lee el dato, se exige que sea string y se aplica trim()
function capturar($tipo, $campo) {
    $valor = filter_input($tipo, $campo, FILTER_UNSAFE_RAW);
    return is_string($valor) ? trim($valor) : '';
}

// "Base de datos" simple de tiendas para buscar
$tiendas = [
    ['nombre' => 'Nike',   'categoria' => 'deportiva', 'url' => 'nike.php',
     'desc' => 'Ropa deportiva y sneakers de vanguardia. Zapatillas, remeras y running.'],
    ['nombre' => 'Adidas', 'categoria' => 'deportiva', 'url' => 'adidas.php',
     'desc' => 'Ropa deportiva y moda streetwear. Zapatillas clásicas y buzos de tres tiras.'],
    ['nombre' => 'Zara',   'categoria' => 'casual',    'url' => 'zara.php',
     'desc' => 'Ropa casual y tendencias de moda urbana. Camisas, pantalones y accesorios.'],
];
$categoriasValidas = ['deportiva' => 'Deportiva', 'casual' => 'Casual'];

$q = '';
$categoria = '';
$errores = [];
$resultados = [];
$buscado = false;

// El formulario se considera enviado si llega el parámetro "buscar" por GET
if (isset($_GET['buscar'])) {
    $buscado = true;

    // Sanitización
    $q = capturar(INPUT_GET, 'q');
    $categoria = capturar(INPUT_GET, 'categoria');

    // Validación
    if (empty($q) && empty($categoria)) {
        $errores[] = "Ingresá un término de búsqueda o elegí una categoría.";
    }
    if (!empty($q) && mb_strlen($q) < 2) {
        $errores[] = "El término de búsqueda debe tener al menos 2 caracteres.";
    }
    if (mb_strlen($q) > 50) {
        $errores[] = "El término de búsqueda no puede superar los 50 caracteres.";
    }
    if (!empty($categoria) && !array_key_exists($categoria, $categoriasValidas)) {
        $errores[] = "La categoría seleccionada no es válida.";
        $categoria = '';
    }

    // Filtrado
    if (empty($errores)) {
        foreach ($tiendas as $t) {
            $coincideTexto = empty($q)
                || mb_stripos($t['nombre'], $q) !== false
                || mb_stripos($t['desc'], $q) !== false;
            $coincideCat = empty($categoria) || $t['categoria'] === $categoria;
            if ($coincideTexto && $coincideCat) {
                $resultados[] = $t;
            }
        }
    }
}

$baseHref = '../'; // las rutas de css, imágenes y menú se resuelven desde la raíz
include '../includes/header.php';
?>

        <div class="header-seccion">
            <h1>Buscar Tiendas</h1>
            <p>Encontrá rápidamente una marca por nombre o filtrá por categoría de indumentaria.</p>
        </div>

        <div class="bloque-producto" style="max-width: 500px; margin: 0 auto 20px auto;">
            <form id="form-buscar" action="clase5/buscar.php" method="GET" novalidate>
                <div style="margin-bottom: 15px;">
                    <label for="q" style="display: block; margin-bottom: 5px;">Buscar:</label>
                    <input type="text" id="q" name="q" value="<?php echo e($q); ?>" placeholder="Ej: Nike, zapatillas, camisas..." style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="categoria" style="display: block; margin-bottom: 5px;">Categoría:</label>
                    <select id="categoria" name="categoria" style="width: 100%; padding: 8px;">
                        <option value="">Todas</option>
                        <?php foreach ($categoriasValidas as $clave => $etiqueta): ?>
                            <option value="<?php echo e($clave); ?>" <?php echo ($categoria === $clave) ? 'selected' : ''; ?>><?php echo e($etiqueta); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="buscar" value="1" class="boton-link" style="width: 100%; cursor: pointer;">Buscar</button>
            </form>
        </div>

        <?php if ($buscado && !empty($errores)): ?>
            <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php foreach ($errores as $err): ?>
                    <div>❌ <?php echo e($err); ?></div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($buscado): ?>
            <p>
                <?php echo count($resultados); ?> resultado(s)
                <?php if (!empty($q)): ?>para "<strong><?php echo e($q); ?></strong>"<?php endif; ?>
                <?php if (!empty($categoria)): ?>en categoría <strong><?php echo e($categoriasValidas[$categoria]); ?></strong><?php endif; ?>
            </p>

            <?php if (empty($resultados)): ?>
                <div style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 10px; border-radius: 5px;">
                    No se encontraron tiendas que coincidan con tu búsqueda.
                </div>
            <?php endif; ?>

            <?php foreach ($resultados as $t): ?>
                <div class="tarjeta-tienda">
                    <h2><?php echo e($t['nombre']); ?></h2>
                    <p><?php echo e($t['desc']); ?></p>
                    <div class="botones-accion">
                        <a href="<?php echo e($t['url']); ?>" class="boton-link">Ver catálogo</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="bloque-enlaces">
            <br>
            <a href="clase5/tiendas.php" class="boton-link">Volver a Tiendas</a>
        </div>

<?php include '../includes/footer.php'; ?>
