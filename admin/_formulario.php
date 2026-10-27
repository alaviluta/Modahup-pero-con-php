<?php
// =====================================================================
// admin/_formulario.php  |  Formulario compartido por crear.php y editar.php
// Variables esperadas: $formAction, $textoBoton, $datos, $errores,
//                      $marcas, $categorias, $idProducto (null al crear)
// =====================================================================
?>
        <div class="bloque-producto" style="max-width: 600px; margin: 0 auto;">

            <?php if (!empty($errorGeneral)): ?>
                <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ❌ <?php echo e($errorGeneral); ?>
                </div>
            <?php elseif (!empty($errores)): ?>
                <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ❌ El formulario contiene errores. Revisá los campos marcados e intentá nuevamente.
                </div>
            <?php endif; ?>

            <form action="<?php echo e($formAction); ?>" method="POST" novalidate>

                <?php if ($idProducto !== null): ?>
                    <input type="hidden" name="id" value="<?php echo e($idProducto); ?>">
                <?php endif; ?>

                <div style="margin-bottom: 15px;">
                    <label for="nombre" style="display: block; margin-bottom: 5px;">Nombre del producto:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo e($datos['nombre']); ?>" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['nombre']) ? e($errores['nombre']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="descripcion" style="display: block; margin-bottom: 5px;">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4" style="width: 100%; padding: 8px;"><?php echo e($datos['descripcion']); ?></textarea>
                    <small style="color: red;"><?php echo isset($errores['descripcion']) ? e($errores['descripcion']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="precio" style="display: block; margin-bottom: 5px;">Precio ($):</label>
                    <input type="text" id="precio" name="precio" value="<?php echo e($datos['precio']); ?>" placeholder="Ej: 150000.00" style="width: 100%; padding: 8px;">
                    <small style="color: red;"><?php echo isset($errores['precio']) ? e($errores['precio']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="id_marca" style="display: block; margin-bottom: 5px;">Marca:</label>
                    <select id="id_marca" name="id_marca" style="width: 100%; padding: 8px;">
                        <option value="">-- Elegí una marca --</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?php echo e($m['id_marca']); ?>" <?php echo ((string)$datos['id_marca'] === (string)$m['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo e($m['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: red;"><?php echo isset($errores['id_marca']) ? e($errores['id_marca']) : ''; ?></small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="id_categoria" style="display: block; margin-bottom: 5px;">Categoría:</label>
                    <select id="id_categoria" name="id_categoria" style="width: 100%; padding: 8px;">
                        <option value="">-- Elegí una categoría --</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?php echo e($c['id_categoria']); ?>" <?php echo ((string)$datos['id_categoria'] === (string)$c['id_categoria']) ? 'selected' : ''; ?>>
                                <?php echo e($c['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: red;"><?php echo isset($errores['id_categoria']) ? e($errores['id_categoria']) : ''; ?></small>
                </div>

                <button type="submit" class="boton-link" style="width: 100%; cursor: pointer;"><?php echo e($textoBoton); ?></button>
            </form>

            <a href="admin/index.php" class="boton-link" style="background-color: #6c757d;">Cancelar y volver</a>
        </div>
