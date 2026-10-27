-- =====================================================================
-- migracion_usuarios_empresas.sql  |  Registro de usuarios y empresas
-- Ejecutar UNA SOLA VEZ en phpMyAdmin (pestaña SQL), después de schema.sql.
-- =====================================================================

USE tienda_ropa;

-- 1) Nuevo rol: las empresas pueden entrar al panel y gestionar productos
INSERT INTO roles (id_rol, nombre, descripcion) VALUES
    (3, 'empresa', 'Empresa o marca que gestiona sus productos en el catálogo');

-- 2) Nuevo campo para guardar el nombre de la empresa (queda NULL en los clientes)
ALTER TABLE usuarios
    ADD COLUMN empresa VARCHAR(100) NULL AFTER telefono;

-- Verificación
SELECT id_rol, nombre FROM roles;
DESCRIBE usuarios;
