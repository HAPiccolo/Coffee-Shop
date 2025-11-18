  <?php
    if ($_SESSION['usuario_rol'] != 1) {
        header("Location: no_autorizado.php");
        exit;
    } ?>
  <div class="contenido">
      <form action="" method="post" enctype="multipart/form-data">
          <div class="add_productos">
              <label for="nombre_producto">Nombre del Producto</label>
              <input type="text" name="nombre_producto" id="nombre_producto" required>

              <label for="descripcion_producto">Descripción</label>
              <input type="text" name="descripcion_producto" id="descripcion_producto">

              <label for="precio_producto">Precio</label>
              <input type="text" name="precio_producto" id="precio_producto">

              <!-- CAMBIO 2: Añadir el campo para subir imagen -->
              <label for="imagen_producto">Imagen del Producto (Opcional)</label>
              <input type="file" name="imagen_producto" id="imagen_producto" accept="image/*">

              <input class="btn_guardar" type="submit" name="guardar_producto" id="btn_guardar" value="GUARDAR PRODUCTO">
              <br>
              <!-- Mensaje para el usuario (éxito o error) -->
              <?php

                if (isset($mensaje)): ?>
                  <div class="mensaje <?php echo (strpos($mensaje, 'éxito') !== false) ? 'exito' : 'error'; ?>">
                      <?php echo $mensaje; ?>
                  </div>
              <?php endif; ?>
          </div>

      </form>
  </div>