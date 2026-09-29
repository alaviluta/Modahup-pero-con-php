<?php
// Cargamos las variables de entorno
$env = include __DIR__ . '/../env.php';

// Si la página no definió un título específico, usamos el del env
if (!isset($pageTitle)) {
    $pageTitle = $env['app_title_default'];
} else {
    // Si la página definió un título, le podemos concatenar el nombre del sitio definido en el env
    $pageTitle = $pageTitle . " - " . $env['app_name'];
}

if (!isset($pageDescription)) {
    $pageDescription = "Explorá el catálogo de tiendas de ropa urbana y deportiva más populares.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <nav>
        <a href="index.php" class="nav-btn">Inicio</a>
        <a href="tiendas.php" class="nav-btn">Tiendas</a>
        <a href="nike.php" class="nav-btn">Nike</a>
        <a href="adidas.php" class="nav-btn">Adidas</a>
        <a href="zara.php" class="nav-btn">Zara</a>
        <a href="ranking.php" class="nav-btn">Ranking</a>
        <a href="blog.php" class="nav-btn">Noticias</a>
        <a href="extras.php" class="nav-btn">Extras</a>
        <a href="preguntas.php" class="nav-btn">Preguntas</a>
        <a href="contacto.php" class="nav-btn">Contacto</a>
        <a href="cv.php" class="nav-btn">CV</a>
        <a href="curso.php" class="nav-btn">Curso</a>
        <a href="mapa.php" class="nav-btn">Mapa</a>
        <a href="iniciodesesion.php" class="nav-btn nav-login">Inicio de Sesión</a>
        <button id="toggle-theme" class="nav-btn theme-btn">🌙 Modo Oscuro</button>
    </nav>

    <div class="contenedor">
        <div id="fecha-hora" class="fecha-hora-box"></div>
