<?php
// =====================================================================
// config/funciones.php  |  Funciones auxiliares del CRUD (TP7)
// =====================================================================

// Escapa cualquier valor antes de imprimirlo en HTML (prevención XSS)
function e($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

// Captura un dato de POST/GET con filter_input, exige string y aplica trim()
function capturar($tipo, $campo) {
    $valor = filter_input($tipo, $campo, FILTER_UNSAFE_RAW);
    return is_string($valor) ? trim($valor) : '';
}

// Valida los datos de un producto. Devuelve un arreglo con los errores encontrados.
// $marcas y $categorias son las listas leídas de la base (para validar que el id exista).
function validarProducto($datos, $marcas, $categorias) {
    $errores = [];

    // Nombre
    if (empty($datos['nombre'])) {
        $errores['nombre'] = "El nombre es obligatorio.";
    } elseif (mb_strlen($datos['nombre']) < 2 || mb_strlen($datos['nombre']) > 100) {
        $errores['nombre'] = "El nombre debe tener entre 2 y 100 caracteres.";
    }

    // Descripción (opcional)
    if (mb_strlen($datos['descripcion']) > 1000) {
        $errores['descripcion'] = "La descripción no puede superar los 1000 caracteres.";
    }

    // Precio
    $precio = str_replace(',', '.', $datos['precio']);
    if ($datos['precio'] === '') {
        $errores['precio'] = "El precio es obligatorio.";
    } elseif (!is_numeric($precio) || (float)$precio < 0 || (float)$precio > 99999999.99) {
        $errores['precio'] = "Ingresá un precio válido (número mayor o igual a 0).";
    }

    // Marca: debe existir en la base
    $idsMarcas = array_map('intval', array_column($marcas, 'id_marca'));
    if ($datos['id_marca'] === '' || !in_array((int)$datos['id_marca'], $idsMarcas, true)) {
        $errores['id_marca'] = "Elegí una marca válida.";
    }

    // Categoría: debe existir en la base
    $idsCategorias = array_map('intval', array_column($categorias, 'id_categoria'));
    if ($datos['id_categoria'] === '' || !in_array((int)$datos['id_categoria'], $idsCategorias, true)) {
        $errores['id_categoria'] = "Elegí una categoría válida.";
    }

    return $errores;
}
