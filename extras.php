<?php
$pageTitle = "Extras y Galería - Catálogo de Ropa";
$pageDescription = "Explorá nuestra galería interactiva de imágenes y contenidos adicionales de moda urbana y deportiva.";
include 'includes/header.php';
?>

        <div class="header-seccion">
            <h1>Galería de Extras y Contenidos</h1>
            <p>Navegá a través de nuestra galería interactiva para visualizar en detalle las prendas, estilos urbanos y zapatillas destacadas de las marcas.</p>
        </div>

        <div class="bloque-producto" style="text-align: center;">
            <h2>Galería de Imágenes Interactiva</h2>
            
            <div class="imagen-contenedor" style="margin: 20px 0;">
                <!-- Imagen inicial de la galería -->
                <img id="galeria-img" src="imagenes/ropaurbana.png" width="300" alt="Galería de ropa urbana y deportiva" class="img-ampliable">
            </div>

            <div class="botones-galeria" style="margin-top: 15px;">
                <button id="btn-prev" class="boton-link" style="cursor: pointer; margin-right: 10px;">⬅ Anterior</button>
                <button id="btn-next" class="boton-link" style="cursor: pointer;">Siguiente ➡</button>
            </div>
        </div>

        <div class="bloque-enlaces">
            <br>
            <a href="tiendas.php" class="boton-link">Ver tiendas asociadas</a>
        </div>

<?php include 'includes/footer.php'; ?>