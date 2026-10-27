<?php
$pageTitle = "Crear Cuenta - Catálogo de Ropa";
$pageDescription = "Registrate como cliente o como empresa para acceder a las funciones del portal de indumentaria.";

require_once 'config/conexion.php';
require_once 'config/funciones.php';
require_once 'config/auth.php';

// Si ya hay sesión iniciada no tiene sentido registrarse de nuevo
if (usuarioActual() !== null) {
    header('Location: index.php');
    exit;
}

$errores = [];
$errorGeneral = '';
$datos = [
    'tipo'     => 'cliente',
    'empresa'  => '',
    'nombre'   => '',
    'apellido' => '',
    'email'    => '',
    'telefono' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Captura y sanitización
    $datos['tipo']     = capturar(INPUT_POST, 'tipo');
    $datos['empresa']  = capturar(INPUT_POST, 'empresa');
    $datos['nombre']   = capturar(INPUT_POST, 'nombre');
    $datos['apellido'] = capturar(INPUT_POST, 'apellido');
    $datos['email']    = capturar(INPUT_POST, 'email');
    $datos['telefono'] = capturar(INPUT_POST, 'telefono');

    // Las contraseñas no se recortan ni se vuelven a mostrar en el formulario
    $password  = filter_input(INPUT_POST, 'password',  FILTER_UNSAFE_RAW);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_UNSAFE_RAW);
    $password  = is_string($password)  ? $password  : '';
    $password2 = is_string($password2) ? $password2 : '';

    // 2. Validación en servidor
    if (!in_array($datos['tipo'], ['cliente', 'empresa'], true)) {
        $errores['tipo'] = "Elegí un tipo de cuenta válido.";
    }

    if ($datos['tipo'] === 'empresa') {
        if (empty($datos['empresa'])) {
            $errores['empresa'] = "Ingresá el nombre de la empresa.";
        } elseif (mb_strlen($datos['empresa']) < 2 || mb_strlen($datos['empresa']) > 100) {
            $errores['empresa'] = "El nombre de la empresa debe tener entre 2 y 100 caracteres.";
        }
    }

    if (empty($datos['nombre'])) {
        $errores['nombre'] = "El nombre es obligatorio.";
    } elseif (mb_strlen($datos['nombre']) < 2 || mb_strlen($datos['nombre']) > 50) {
        $errores['nombre'] = "El nombre debe tener entre 2 y 50 caracteres.";
    }

    if (empty($datos['apellido'])) {
        $errores['apellido'] = "El apellido es obligatorio.";
    } elseif (mb_strlen($datos['apellido']) < 2 || mb_strlen($datos['apellido']) > 50) {
        $errores['apellido'] = "El apellido debe tener entre 2 y 50 caracteres.";
    }

    if (empty($datos['email'])) {
        $errores['email'] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($datos['email']) > 100) {
        $errores['email'] = "El formato del correo electrónico no es válido.";
    }

    if ($datos['telefono'] !== '' && !preg_match('/^[0-9+\-\s()]{6,20}$/', $datos['telefono'])) {
        $errores['telefono'] = "El teléfono solo puede tener números, espacios, + - ( ) (6 a 20 caracteres).";
    }

    if ($password === '') {
        $errores['password'] = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 8 || strlen($password) > 72) {
        $errores['password'] = "La contraseña debe tener entre 8 y 72 caracteres.";
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errores['password'] = "La contraseña debe incluir al menos una letra y un número.";
    }

    if ($password2 !== $password) {
        $errores['password2'] = "Las contraseñas no coinciden.";
    }

    // 3. Comprobaciones en la base y alta del usuario
    if (empty($errores)) {
        try {
            // El email no puede estar registrado
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
            $stmt->execute([':email' => $datos['email']]);
            if ($stmt->fetch()) {
                $errores['email'] = "Ya existe una cuenta con ese correo electrónico.";
            }

            // Rol según el tipo de cuenta
            $stmt = $pdo->prepare("SELECT id_rol FROM roles WHERE nombre = :nombre");
            $stmt->execute([':nombre' => $datos['tipo']]);
            $rol = $stmt->fetch();
            if (!$rol) {
                $errorGeneral = "El rol '" . $datos['tipo'] . "' no existe en la base de datos. "
                              . "Ejecutá clase7/migracion_usuarios_empresas.sql en phpMyAdmin.";
            }

            if (empty($errores) && $errorGeneral === '') {
                $sql = "INSERT INTO usuarios (nombre, apellido, email, password, telefono, empresa, id_rol)
                        VALUES (:nombre, :apellido, :email, :password, :telefono, :empresa, :id_rol)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre'   => $datos['nombre'],
                    ':apellido' => $datos['apellido'],
                    ':email'    => $datos['email'],
                    ':password' => password_hash($password, PASSWORD_DEFAULT),
                    ':telefono' => ($datos['telefono'] !== '') ? $datos['telefono'] : null,
                    ':empresa'  => ($datos['tipo'] === 'empresa') ? $datos['empresa'] : null,
                    ':id_rol'   => (int)$rol['id_rol'],
                ]);

                setFlash('ok', '✅ ¡Cuenta creada correctamente! Ya podés iniciar sesión con tu correo y contraseña.');
                header('Location: iniciodesesion.php');
                exit;
            }
        } catch (PDOException $ex) {
            error_log("Error al registrar usuario: " . $ex->getMessage());
            $errorGeneral = "No se pudo crear la cuenta. Intentá nuevamente.";
            if ($env['env'] === 'development') {
                $errorGeneral .= " Detalle (solo visible en desarrollo): " . $ex->getMessage()
                               . " — ¿Ejecutaste clase7/migracion_usuarios_empresas.sql?";
            }
        }
    }
}

include 'includes/header.php';
?>

        <div class="header-seccion">
            <h1>Crear Cuenta</h1>
            <p>Registrate como <strong>cliente</strong> o como <strong>empresa</strong>. Las empresas pueden entrar al panel y gestionar sus productos en el catálogo.</p>
        </div>

        <div class="bloque-producto" style="max-width: 500px; margin: 0 auto;">

            <?php if ($errorGeneral !== ''): ?>
                <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ❌ <?php echo e($errorGeneral); ?>
                </div>
            <?php elseif (!empty($errores)): ?>
                <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ❌ El formulario contiene errores. Revisá los campos marcados e intentá nuevamente.
                </div>
            <?php endif; ?>

            <form id="form-registro" action="registro.php" method="POST" novalidate>

                <div style="margin-bottom: 15px;">
                    <label for="tipo" style="display: block; margin-bottom: 5px;">Tipo de cuenta:</label>
                    <select id="tipo" name="tipo" style="width: 100%; padding: 8px;">
                        <option value="cliente" <?php echo ($datos['tipo'] === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                        <option value="empresa" <?php echo ($datos['tipo'] === 'empresa') ? 'selected' : ''; ?>>Empresa</option>
                    </select>
                    <small style="color: red;"><?php echo isset($errores['tipo']) ? e($errores['tipo']) : ''; ?></small>
                </div>

                <div id="bloque-empresa" style="margin-bottom: 15px;">
                    <label for="empresa" style="display: block; margin-bottom: 5px;">Nombre de la empresa:</label>
                    <input type="text" id="empresa" name="empresa" value="<?php echo e($datos['empresa']); ?>" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['empresa']) ? e($errores['empresa']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="nombre" style="display: block; margin-bottom: 5px;">Nombre<span id="aclaracion-nombre"></span>:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo e($datos['nombre']); ?>" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['nombre']) ? e($errores['nombre']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="apellido" style="display: block; margin-bottom: 5px;">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo e($datos['apellido']); ?>" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['apellido']) ? e($errores['apellido']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Correo electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo e($datos['email']); ?>" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['email']) ? e($errores['email']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="telefono" style="display: block; margin-bottom: 5px;">Teléfono (opcional):</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo e($datos['telefono']); ?>" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['telefono']) ? e($errores['telefono']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px;">Contraseña (mínimo 8 caracteres, con letras y números):</label>
                    <input type="password" id="password" name="password" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['password']) ? e($errores['password']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password2" style="display: block; margin-bottom: 5px;">Repetir contraseña:</label>
                    <input type="password" id="password2" name="password2" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['password2']) ? e($errores['password2']) : ''; ?></small>
                </div>

                <button type="submit" class="boton-link" style="width: 100%; cursor: pointer;">Crear cuenta</button>
            </form>

            <p style="margin-top: 15px; text-align: center;">¿Ya tenés cuenta? <a href="iniciodesesion.php">Iniciá sesión</a></p>
        </div>

        <script>
            // Muestra el campo "Nombre de la empresa" solo si se elige cuenta de empresa
            (function () {
                var tipo = document.getElementById('tipo');
                var bloqueEmpresa = document.getElementById('bloque-empresa');
                var aclaracion = document.getElementById('aclaracion-nombre');

                function actualizar() {
                    var esEmpresa = (tipo.value === 'empresa');
                    bloqueEmpresa.style.display = esEmpresa ? 'block' : 'none';
                    aclaracion.textContent = esEmpresa ? ' del responsable' : '';
                }

                tipo.addEventListener('change', actualizar);
                actualizar();
            })();
        </script>

<?php include 'includes/footer.php'; ?>
