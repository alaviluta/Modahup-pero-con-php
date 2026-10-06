<?php
$pageTitle = "Inicio de Sesión - Catálogo de Ropa";
$pageDescription = "Iniciá sesión en tu cuenta para acceder a funciones exclusivas del portal de indumentaria.";
include 'includes/header.php';
?>

        <div class="header-seccion">
            <h1>Iniciar Sesión</h1>
            <p>Ingresá tus credenciales para acceder a tu cuenta y gestionar tus preferencias en el catálogo.</p>
        </div>

        <div class="bloque-producto" style="max-width: 400px; margin: 0 auto;">
            <form id="form-login" action="#" method="POST">
                <div style="margin-bottom: 15px;">
                    <label for="usuario" style="display: block; margin-bottom: 5px;">Usuario o Correo:</label>
                    <input type="text" id="usuario" name="usuario" style="width: 100%; padding: 8px;">
                    <small id="error-usuario" style="color: red;"></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px;">Contraseña:</label>
                    <input type="password" id="password" name="password" style="width: 100%; padding: 8px;">
                    <small id="error-password" style="color: red;"></small>
                </div>

                <button type="submit" class="boton-link" style="width: 100%; cursor: pointer;">Ingresar</button>
            </form>
        </div>

<?php include 'includes/footer.php'; ?>