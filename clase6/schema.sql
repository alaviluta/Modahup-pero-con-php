-- =====================================================================
-- schema.sql  |  TP6 - Base de datos del proyecto ModaHup
-- Catálogo de Tiendas de Ropa Deportiva y Urbana
-- Motor: MySQL / MariaDB (XAMPP, Laragon, phpMyAdmin)
-- =====================================================================

-- 1) Creación de la base de datos
-- (el nombre coincide con 'db_name' de env.php)
CREATE DATABASE IF NOT EXISTS tienda_ropa
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tienda_ropa;

-- ---------------------------------------------------------------------
-- 2) Limpieza previa (permite volver a ejecutar el script sin errores)
--    ATENCIÓN: borra las tablas y sus datos si ya existían.
-- ---------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS consultas;
DROP TABLE IF EXISTS producto_variantes;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS estados_consulta;
DROP TABLE IF EXISTS colores;
DROP TABLE IF EXISTS talles;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS marcas;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- 3) TABLAS MAESTRAS / SECUNDARIAS
-- =====================================================================

-- Roles de usuario (administrador, cliente, etc.)
CREATE TABLE roles (
    id_rol      INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(30)  NOT NULL UNIQUE,
    descripcion VARCHAR(150) NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Marcas (Nike, Adidas, Zara...)
CREATE TABLE marcas (
    id_marca    INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(50)  NOT NULL UNIQUE,
    pais_origen VARCHAR(50)  NOT NULL,
    sitio_web   VARCHAR(150) NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categorías de indumentaria (calzado, remeras, pantalones...)
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(50)  NOT NULL UNIQUE,
    descripcion  VARCHAR(200) NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Talles (S, M, L, 38, 39, 40...)
CREATE TABLE talles (
    id_talle   INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(10) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Colores (Negro, Blanco, Azul...)
CREATE TABLE colores (
    id_color   INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(30) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estados posibles de una consulta de contacto
CREATE TABLE estados_consulta (
    id_estado  INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(30) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 4) TABLA DE USUARIOS (autenticación, rol y datos personales)
-- =====================================================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(50)  NOT NULL,
    apellido   VARCHAR(50)  NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,          -- se guarda el HASH, nunca la clave en texto plano
    telefono   VARCHAR(20)  NULL,
    id_rol     INT          NOT NULL,
    activo     TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuarios_roles
        FOREIGN KEY (id_rol) REFERENCES roles (id_rol)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 5) TABLA PRINCIPAL DE NEGOCIO: PRODUCTOS
-- =====================================================================
CREATE TABLE productos (
    id_producto  INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100)  NOT NULL,
    descripcion  TEXT          NULL,
    precio       DECIMAL(10,2) NOT NULL,
    id_marca     INT           NOT NULL,
    id_categoria INT           NOT NULL,
    activo       TINYINT(1)    NOT NULL DEFAULT 1,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_productos_marcas
        FOREIGN KEY (id_marca) REFERENCES marcas (id_marca)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_productos_categorias
        FOREIGN KEY (id_categoria) REFERENCES categorias (id_categoria)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 6) TABLA DE RELACIÓN / DETALLE (N:M entre productos, talles y colores)
--    Un producto tiene muchas combinaciones talle/color, cada una con su stock.
-- =====================================================================
CREATE TABLE producto_variantes (
    id_variante INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_talle    INT NOT NULL,
    id_color    INT NOT NULL,
    stock       INT NOT NULL DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- Evita repetir la misma combinación producto + talle + color
    CONSTRAINT uq_variante UNIQUE (id_producto, id_talle, id_color),
    CONSTRAINT fk_variantes_productos
        FOREIGN KEY (id_producto) REFERENCES productos (id_producto)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_variantes_talles
        FOREIGN KEY (id_talle) REFERENCES talles (id_talle)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_variantes_colores
        FOREIGN KEY (id_color) REFERENCES colores (id_color)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 7) CONSULTAS DE CONTACTO (formulario de contacto del sitio)
-- =====================================================================
CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario  INT          NULL,               -- NULL si la consulta la envió un visitante sin cuenta
    nombre      VARCHAR(50)  NOT NULL,
    email       VARCHAR(100) NOT NULL,
    mensaje     TEXT         NOT NULL,
    id_estado   INT          NOT NULL DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_consultas_usuarios
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT fk_consultas_estados
        FOREIGN KEY (id_estado) REFERENCES estados_consulta (id_estado)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 8) ÍNDICES EXPLÍCITOS
-- =====================================================================

-- Búsqueda de productos por nombre (la usa el buscador del sitio)
CREATE INDEX idx_productos_nombre ON productos (nombre);

-- Filtrado de consultas por email del remitente
CREATE INDEX idx_consultas_email ON consultas (email);

-- Fin del script
