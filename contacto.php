<?php
$pageTitle = "Contacto - Catálogo de Ropa Deportiva y Urbana";
$pageDescription = "Envianos tus consultas, dudas o sugerencias a través de nuestro formulario de contacto oficial.";
include 'includes/header.php';
?>

        <div class="header-seccion">
            <h1>Contacto y Soporte</h1>
            <p>¿Tenés alguna duda sobre los catálogos o querés dejarnos una sugerencia? Completá el formulario a continuación y nos pondremos en contacto con vos a la brevedad.</p>
        </div>

        <div class="bloque-producto" style="max-width: 500px; margin: 0 auto;">
            <form id="form-contacto" action="#" method="POST">
                <div style="margin-bottom: 15px;">
                    <label for="nombre" style="display: block; margin-bottom: 5px;">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" style="width: 100%; padding: 8px;">
                    <small id="error-nombre" style="color: red;"></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Correo electrónico:</label>
                    <input type="email" id="email" name="email" style="width: 100%; padding: 8px;">
                    <small id="error-email" style="color: red;"></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="mensaje" style="display: block; margin-bottom: 5px;">Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" rows="4" style="width: 100%; padding: 8px;"></textarea>
                    <small id="error-mensaje" style="color: red;"></small>
                </div>

                <button type="submit" class="boton-link" style="width: 100%; cursor: pointer;">Enviar Mensaje</button>
            </form>
        </div>

<?php include 'includes/footer.php'; ?>