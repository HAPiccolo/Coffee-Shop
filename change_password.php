  <?php
    if ($_SESSION['usuario_rol'] != 1) {
        header("Location: no_autorizado.php");
        exit;
    } ?>
  <div class="contenido">
      <form action="" method="post">
          <div class="add_productos">
              <label for="password_actual">Contraseña actual</label>
              <input type="password" name="password_actual" id="password_actual" required>

              <label for="password_nueva">Nueva contraseña</label>
              <input type="password" name="password_nueva" id="password_nueva" required>

              <label for="password_repetir">Repita la nueva contraseña</label>
              <input type="password" name="password_repetir" id="password_repetir" required>

              <input type="submit" class="btn_guardar" name="actualizar_password" id="btn_guardar" value="GUARDAR">
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