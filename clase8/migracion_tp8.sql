-- =====================================================================
-- migracion_tp8.sql  |  TP8 - Sesiones, roles y control de acceso
-- Ejecutar en phpMyAdmin (pestaña SQL) con la base tienda_ropa.
-- Se puede ejecutar más de una vez sin problemas.
-- =====================================================================

USE tienda_ropa;

-- 1) Roles con los nombres que pide el TP8: 'admin' y 'usuario'
--    (el rol 'empresa' se mantiene)
UPDATE roles
SET nombre = 'admin', descripcion = 'Administrador del sistema'
WHERE nombre = 'administrador';

UPDATE roles
SET nombre = 'usuario', descripcion = 'Usuario registrado del sitio'
WHERE nombre = 'cliente';

-- 2) Vista con la estructura recomendada por el TP8:
--    id, nombre, email, password, rol, created_at
--    (la tabla real está normalizada: el rol vive en la tabla roles, ver TP6)
CREATE OR REPLACE VIEW vista_usuarios AS
SELECT u.id_usuario                         AS id,
       CONCAT(u.nombre, ' ', u.apellido)    AS nombre,
       u.email                              AS email,
       u.password                           AS password,
       r.nombre                             AS rol,
       u.created_at                         AS created_at
FROM usuarios u
INNER JOIN roles r ON u.id_rol = r.id_rol;

-- 3) Verificación (no se muestra la contraseña)
SELECT id_rol, nombre FROM roles;
SELECT id, nombre, email, rol, created_at FROM vista_usuarios;
