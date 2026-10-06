<?php
$pageTitle = "Contacto - Catálogo de Ropa Deportiva y Urbana";
$pageDescription = "Envianos tus consultas, dudas o sugerencias a través de nuestro formulario de contacto oficial.";

// Captura segura: filter_input() lee el dato, se exige que sea string y se aplica trim()
function capturar($tipo, $campo) {
    $valor = filter_input($tipo, $campo, FILTER_UNSAFE_RAW);
    return is_string($valor) ? trim($valor) : '';
}

// Helper para escapar cualquier dato antes de mostrarlo (prevención XSS)
function e($valor) {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

$nombre = '';
$email = '';
$mensaje = '';
$errores = [];
$exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Captura y sanitización (filter_input + trim)
    $nombre  = capturar(INPUT_POST, 'nombre');
    $email   = capturar(INPUT_POST, 'email');
    $mensaje = capturar(INPUT_POST, 'mensaje');

    // 2. Validación en servidor
    if (empty($nombre)) {
        $errores['nombre'] = "El nombre es obligatorio.";
    } elseif (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 50) {
        $errores['nombre'] = "El nombre debe tener entre 2 y 50 caracteres.";
    }

    if (empty($email)) {
        $errores['email'] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "El formato del correo electrónico no es válido.";
    }

    if (empty($mensaje)) {
        $errores['mensaje'] = "El mensaje es obligatorio.";
    } elseif (mb_strlen($mensaje) < 10) {
        $errores['mensaje'] = "El mensaje debe tener al menos 10 caracteres.";
    } elseif (mb_strlen($mensaje) > 1000) {
        $errores['mensaje'] = "El mensaje no puede superar los 1000 caracteres.";
    }

    // 3. Resultado
    if (empty($errores)) {
        $exito = true;
        $nombreExito = $nombre;
        // Se limpian los campos solo si el envío fue exitoso
        $nombre = $email = $mensaje = '';
    }
}

$baseHref = '../'; // las rutas de css, imágenes y menú se resuelven desde la raíz
include '../includes/header.php';
?>

        <div class="header-seccion">
            <h1>Contacto y Soporte</h1>
            <p>¿Tenés alguna duda sobre los catálogos o querés dejarnos una sugerencia? Completá el formulario a continuación y nos pondremos en contacto con vos a la brevedad.</p>
        </div>

        <div class="bloque-producto" style="max-width: 500px; margin: 0 auto;">

            <?php if ($exito): ?>
                <div style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ✅ ¡Gracias, <?php echo e($nombreExito); ?>! Tu mensaje fue enviado correctamente.
                </div>
            <?php elseif (!empty($errores)): ?>
                <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ❌ El formulario contiene errores. Revisá los campos marcados e intentá nuevamente.
                </div>
            <?php endif; ?>

            <form id="form-contacto" action="clase5/contacto.php" method="POST" novalidate>
                <div style="margin-bottom: 15px;">
                    <label for="nombre" style="display: block; margin-bottom: 5px;">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo e($nombre); ?>" style="width: 100%; padding: 8px;">
                    <small id="error-nombre" style="color: red;"><?php echo isset($errores['nombre']) ? e($errores['nombre']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Correo electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo e($email); ?>" style="width: 100%; padding: 8px;">
                    <small id="error-email" style="color: red;"><?php echo isset($errores['email']) ? e($errores['email']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="mensaje" style="display: block; margin-bottom: 5px;">Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" rows="4" style="width: 100%; padding: 8px;"><?php echo e($mensaje); ?></textarea>
                    <small id="error-mensaje" style="color: red;"><?php echo isset($errores['mensaje']) ? e($errores['mensaje']) : ''; ?></small>
                </div>

                <button type="submit" class="boton-link" style="width: 100%; cursor: pointer;">Enviar Mensaje</button>
            </form>
        </div>

<?php include '../includes/footer.php'; ?>
