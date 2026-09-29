<?php
$pageTitle = "Tiendas Recomendadas - Catálogo de Ropa Deportiva y Urbana";
$pageDescription = "Explorá el directorio de tiendas recomendadas con las mejores marcas internacionales: Nike, Adidas y Zara. Accedé a sus catálogos oficiales y sitios web.";
include 'includes/header.php';
?>

        <div class="header-seccion">
            <h1>Tiendas Recomendadas de Indumentaria</h1>
            <p>En este directorio reunimos a las principales marcas internacionales de ropa deportiva, calzado y moda casual. Explorá el catálogo exclusivo de cada firma o ingresá directamente a sus plataformas oficiales.</p>
        </div>

        <div class="tarjeta-tienda">
            <h2>Nike</h2>

            <div class="imagen-contenedor">
                <img src="imagenes/nike.png" width="250" alt="Logo oficial de la marca de ropa deportiva Nike" class="img-ampliable">
            </div>

            <p>Ropa deportiva y sneakers de vanguardia.</p>

            <h3>Innovación y Rendimiento Deportivo</h3>
            <p>Nike destaca en el mercado global por su constante avance en tecnología de amortiguación, indumentaria de alto rendimiento para atletas y modelos icónicos de calzado urbano para el uso diario.</p>

            <div class="botones-accion">
                <a href="nike.php" class="boton-link">Ver catálogo</a>
                <a href="https://www.nike.com" target="_blank" rel="noopener" class="boton-link">Ir a Nike</a>
            </div>
        </div>

        <hr>

        <div class="tarjeta-tienda">
            <h2>Adidas</h2>

            <div class="imagen-contenedor">
                <img src="imagenes/adidas.png" width="250" alt="Logo oficial de la marca deportiva Adidas" class="img-ampliable">
            </div>

            <p>Ropa deportiva y moda streetwear.</p>

            <h3>Tradición Clásica y Diseño Moderno</h3>
            <p>Reconocida mundialmente por sus tres tiras, Adidas ofrece soluciones integrales en vestimenta para entrenamiento, botines, zapatillas clásicas y colecciones urbanas de estética atemporal.</p>

            <div class="botones-accion">
                <a href="adidas.php" class="boton-link">Ver catálogo</a>
                <a href="https://www.adidas.com" target="_blank" rel="noopener" class="boton-link">Ir a Adidas</a>
            </div>
        </div>

        <hr>

        <div class="tarjeta-tienda">
            <h2>Zara</h2>

            <div class="imagen-contenedor">
                <img src="imagenes/zara.png" width="250" alt="Logo oficial de la tienda de ropa casual Zara" class="img-ampliable">
            </div>

            <p>Ropa casual y tendencias de moda urbana.</p>

            <h3>Elegancia Cotidiana y Tendencias Globales</h3>
            <p>Zara es una de las referentes principales del segmento fast fashion, brindando prendas de diseño contemporáneo, accesorios elegantes y ropa casual adaptable a todo tipo de ocasiones.</p>

            <div class="botones-accion">
                <a href="zara.php" class="boton-link">Ver catálogo</a>
                <a href="https://www.zara.com" target="_blank" rel="noopener" class="boton-link">Ir a Zara</a>
            </div>
        </div>

<?php include 'includes/footer.php'; ?>