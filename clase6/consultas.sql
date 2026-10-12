-- =====================================================================
-- consultas.sql  |  TP6 - Pruebas CRUD del proyecto ModaHup
-- Ejecutar DESPUÉS de schema.sql
-- =====================================================================

USE tienda_ropa;

-- =====================================================================
-- C - CREATE (inserción de datos de prueba)
-- =====================================================================

-- ---- Tablas maestras / secundarias ----

INSERT INTO roles (id_rol, nombre, descripcion) VALUES
    (1, 'administrador', 'Gestiona el catálogo y las consultas'),
    (2, 'cliente',       'Usuario registrado del sitio');

INSERT INTO marcas (id_marca, nombre, pais_origen, sitio_web) VALUES
    (1, 'Nike',   'Estados Unidos', 'https://www.nike.com'),
    (2, 'Adidas', 'Alemania',       'https://www.adidas.com'),
    (3, 'Zara',   'España',         'https://www.zara.com');

INSERT INTO categorias (id_categoria, nombre, descripcion) VALUES
    (1, 'Calzado deportivo', 'Zapatillas y sneakers para entrenamiento y uso urbano'),
    (2, 'Camisas y remeras', 'Prendas superiores casuales y deportivas'),
    (3, 'Pantalones',        'Pantalones de corte moderno y urbano');

INSERT INTO talles (id_talle, nombre) VALUES
    (1, 'S'),
    (2, 'M'),
    (3, 'L'),
    (4, '39'),
    (5, '40'),
    (6, '41');

INSERT INTO colores (id_color, nombre) VALUES
    (1, 'Negro'),
    (2, 'Blanco'),
    (3, 'Azul');

INSERT INTO estados_consulta (id_estado, nombre) VALUES
    (1, 'Pendiente'),
    (2, 'Respondida'),
    (3, 'Cerrada');

-- ---- Usuarios (la clave es un hash de ejemplo, no una contraseña real) ----

INSERT INTO usuarios (id_usuario, nombre, apellido, email, password, telefono, id_rol) VALUES
    (1, 'Admin', 'ModaHup',  'admin@modahup.com', '$2y$10$EjemploDeHashAdminNoUsarEnProduccion000000000000000', '3870000001', 1),
    (2, 'Ana',   'Gómez',    'ana@gmail.com',     '$2y$10$EjemploDeHashClienteNoUsarEnProduccion00000000000000', '3870000002', 2);

-- ---- Tabla principal: productos (con sus claves foráneas) ----

INSERT INTO productos (id_producto, nombre, descripcion, precio, id_marca, id_categoria) VALUES
    (1, 'Zapatillas Air Max 90',  'Zapatillas con amortiguación avanzada para uso diario y deporte', 150000.00, 1, 1),
    (2, 'Zapatillas Superstar',   'Clásicas zapatillas de tres tiras con diseño urbano',             120000.00, 2, 1),
    (3, 'Camisa de Lino',         'Camisa liviana y respirable de estilo casual',                     45000.00, 3, 2);

-- ---- Tabla de detalle: variantes (talle + color + stock) ----

INSERT INTO producto_variantes (id_producto, id_talle, id_color, stock) VALUES
    (1, 4, 1, 10),   -- Air Max 90, talle 39, Negro
    (1, 4, 2,  5),   -- Air Max 90, talle 39, Blanco
    (1, 5, 1,  8),   -- Air Max 90, talle 40, Negro
    (2, 5, 2,  6),   -- Superstar,  talle 40, Blanco
    (2, 6, 2,  0),   -- Superstar,  talle 41, Blanco (sin stock)
    (3, 1, 3, 12),   -- Camisa de Lino, talle S, Azul
    (3, 2, 3,  7);   -- Camisa de Lino, talle M, Azul

-- ---- Consultas de contacto ----

INSERT INTO consultas (id_usuario, nombre, email, mensaje, id_estado) VALUES
    (2,    'Ana',  'ana@gmail.com',   'Hola, quiero consultar por el stock de las zapatillas Air Max.', 1),
    (NULL, 'Luis', 'luis@gmail.com',  'Mensaje de prueba que luego se va a eliminar.',                  1);


-- =====================================================================
-- R - READ (consultas de lectura)
-- =====================================================================

-- 1) SELECT con WHERE y ORDER BY:
--    productos de más de $100.000, del más caro al más barato
SELECT id_producto, nombre, precio
FROM productos
WHERE precio > 100000
ORDER BY precio DESC;

-- 2) INNER JOIN de 3 tablas:
--    producto con su marca y su categoría
SELECT p.id_producto,
       p.nombre      AS producto,
       m.nombre      AS marca,
       c.nombre      AS categoria,
       p.precio
FROM productos p
INNER JOIN marcas     m ON p.id_marca     = m.id_marca
INNER JOIN categorias c ON p.id_categoria = c.id_categoria
ORDER BY p.nombre ASC;

-- 3) INNER JOIN de 5 tablas:
--    stock disponible por producto, talle y color (solo con stock)
SELECT p.nombre   AS producto,
       t.nombre   AS talle,
       col.nombre AS color,
       v.stock
FROM producto_variantes v
INNER JOIN productos p   ON v.id_producto = p.id_producto
INNER JOIN talles    t   ON v.id_talle    = t.id_talle
INNER JOIN colores   col ON v.id_color    = col.id_color
WHERE v.stock > 0
ORDER BY p.nombre ASC, t.nombre ASC;

-- 4) INNER JOIN de consultas con su estado
SELECT q.id_consulta,
       q.nombre,
       q.email,
       e.nombre AS estado,
       q.created_at
FROM consultas q
INNER JOIN estados_consulta e ON q.id_estado = e.id_estado
ORDER BY q.created_at DESC;


-- =====================================================================
-- U - UPDATE (siempre con WHERE)
-- =====================================================================

-- Cambiar el precio de un producto específico
UPDATE productos
SET precio = 139999.00
WHERE id_producto = 1;

-- Marcar una consulta como "Respondida"
UPDATE consultas
SET id_estado = 2
WHERE id_consulta = 1;

-- Verificación de los cambios
SELECT id_producto, nombre, precio FROM productos WHERE id_producto = 1;
SELECT id_consulta, id_estado       FROM consultas WHERE id_consulta = 1;


-- =====================================================================
-- D - DELETE (siempre con WHERE)
-- =====================================================================

-- Dar de baja la consulta de prueba
DELETE FROM consultas
WHERE id_consulta = 2;

-- Verificación: debe quedar solo la consulta 1
SELECT id_consulta, nombre, email FROM consultas;
