<?php
// Iniciar sesión para poder usar variables de sesión
session_start();

include "auth.php";
verificarAdmin(); // solo admin accede

// Incluimos la conexión a la BD para poder usarla en la lógica de abajo
include "database.php";

// --- INICIO DE LA LÓGICA ---

// Variable para controlar si mostramos el formulario de contraseña
$mostrar_form_password = false;
$mostrar_form_producto = false;
$mostrar_lista_productos = false;
$mostrar_form_edicion = false;

// Variable para guardar el mensaje de éxito o error
$mensaje_password = '';
$mensaje_producto = '';
$mensaje_lista_productos = '';

// Variable para el filtro de productos
$filtro_producto = isset($_GET['filtro']) ? $_GET['filtro'] : '';

// Variable para el producto a editar
$producto_a_editar = null;

// Leer mensaje de la URL
$mensaje_url = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';

// Leer mensaje de sesión (para mostrar después de redirecciones)
$mensaje_lista_productos = isset($_SESSION['mensaje_lista_productos']) ? $_SESSION['mensaje_lista_productos'] : '';
$mostrar_lista_productos = isset($_SESSION['mostrar_lista_productos']) ? $_SESSION['mostrar_lista_productos'] : false;

// Limpiar la sesión después de leer los mensajes
unset($_SESSION['mensaje_lista_productos']);
unset($_SESSION['mostrar_lista_productos']);

// Si hay filtro en la URL, mostrar la lista automáticamente
if (!empty($filtro_producto)) {
    $mostrar_lista_productos = true;
}

// Lógica para MOSTRAR el formulario cuando se presiona el botón
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'cambiarContrasenia') {
            $mostrar_form_password = true;
            // Limpiamos el mensaje de lista de productos
            $mensaje_lista_productos = '';
        } elseif ($_POST['accion'] === 'nuevoProducto') {
            $mostrar_form_producto = true;
            // Limpiamos el mensaje de lista de productos
            $mensaje_lista_productos = '';
        } elseif ($_POST['accion'] === 'verProductos') {
            $mostrar_lista_productos = true;
            // Limpiamos el mensaje de lista de productos
            $mensaje_lista_productos = '';
        } elseif ($_POST['accion'] === 'filtrar_productos') {
            $filtro_producto = isset($_POST['filtro']) ? $_POST['filtro'] : '';
            $mostrar_lista_productos = true;
        } elseif ($_POST['accion'] === 'editarProducto') {
            $mostrar_lista_productos = false;
            $mostrar_form_edicion = true;
            $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;

            // Depuración: Mostrar el ID que se está recibiendo
            error_log("ID del producto a editar: " . $id_producto);

            // Obtenemos los datos del producto a editar
            try {
                $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
                $stmt->bindParam(':id', $id_producto, PDO::PARAM_INT);
                $stmt->execute();
                $producto_a_editar = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$producto_a_editar) {
                    $mensaje_lista_productos = "Producto no encontrado.";
                    $mostrar_form_edicion = false;
                    $mostrar_lista_productos = true;
                    error_log("Producto no encontrado con ID: " . $id_producto);
                } else {
                    error_log("Producto encontrado: " . $producto_a_editar['nombre_producto']);
                }
            } catch (PDOException $e) {
                $mensaje_lista_productos = "Error al obtener el producto: " . $e->getMessage();
                $mostrar_form_edicion = false;
                $mostrar_lista_productos = true;
                error_log("Error en la consulta: " . $e->getMessage());
            }
        } elseif ($_POST['accion'] === 'eliminarProducto') {
            $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;

            try {
                // Primero obtenemos el nombre de la imagen para eliminar el archivo
                $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = :id");
                $stmt->bindParam(':id', $id_producto, PDO::PARAM_INT);
                $stmt->execute();
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                // Eliminamos el producto de la base de datos
                $stmt_delete = $pdo->prepare("DELETE FROM productos WHERE id = :id");
                $stmt_delete->bindParam(':id', $id_producto, PDO::PARAM_INT);

                if ($stmt_delete->execute()) {
                    // Si el producto tenía una imagen, la eliminamos del servidor
                    if ($producto && $producto['imagen']) {
                        $ruta_imagen = 'uploads/productos/' . $producto['imagen'];
                        if (file_exists($ruta_imagen)) {
                            unlink($ruta_imagen);
                        }
                    }
                    $mensaje_lista_productos = "Producto eliminado con éxito.";
                } else {
                    $mensaje_lista_productos = "Error al eliminar el producto.";
                }
            } catch (PDOException $e) {
                $mensaje_lista_productos = "Error en la base de datos: " . $e->getMessage();
            }

            // Después de eliminar, mostramos la lista actualizada
            $mostrar_lista_productos = true;
        }
    }
}

// Lógica para PROCESAR el formulario de CONTRASEÑA
if (isset($_POST['actualizar_password'])) {
    // ¡Importante! Mantenemos el formulario visible para poder mostrar el mensaje
    $mostrar_form_password = true;

    // Obtener los datos del formulario
    $password_actual = trim($_POST['password_actual']);
    $password_nueva = trim($_POST['password_nueva']);
    $password_repetir = trim($_POST['password_repetir']);

    // Obtener el nombre del usuario logueado desde la sesión
    $nombre_usuario = $_SESSION['usuario_nombre'];

    // Validaciones
    if (empty($password_actual) || empty($password_nueva) || empty($password_repetir)) {
        $mensaje_password = "Todos los campos son obligatorios.";
    } elseif ($password_nueva !== $password_repetir) {
        $mensaje_password = "Las contraseñas no coinciden.";
    } else {
        // Verificar la contraseña actual en la base de datos
        try {
            $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE nombre = :nombre");
            $stmt->bindParam(':nombre', $nombre_usuario, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Comprobar si la contraseña actual es correcta
            if ($usuario && password_verify($password_actual, $usuario['password'])) {

                // Hashear la nueva contraseña
                $nuevo_password_hash = password_hash($password_nueva, PASSWORD_BCRYPT);

                // Actualizar la contraseña en la base de datos
                $stmt_update = $pdo->prepare("UPDATE usuarios SET password = :password WHERE nombre = :nombre");
                $stmt_update->bindParam(':password', $nuevo_password_hash, PDO::PARAM_STR);
                $stmt_update->bindParam(':nombre', $nombre_usuario, PDO::PARAM_STR);

                if ($stmt_update->execute()) {
                    $mensaje_password = "¡Contraseña cambiada con éxito!";
                } else {
                    $mensaje_password = "Error al actualizar la contraseña. Inténtalo de nuevo.";
                }
            } else {
                $mensaje_password = "La contraseña actual que ingresaste es incorrecta.";
            }
        } catch (PDOException $e) {
            $mensaje_password = "Error en la base de datos: " . $e->getMessage();
        }
    }
}

// Lógica para PROCESAR el formulario de PRODUCTO
if (isset($_POST['guardar_producto'])) {

    // ¡Importante! Mantenemos el formulario visible para poder mostrar el mensaje
    $mostrar_form_producto = true;

    // --- INICIO DE LÓGICA DE SUBIDA DE IMAGEN ---

    $nombre_producto = trim($_POST['nombre_producto']);
    $descripcion_producto = trim($_POST['descripcion_producto']);
    $precio_producto = trim($_POST['precio_producto']);
    $nombre_imagen = null; // Por defecto, no hay imagen

    // Verificar si se subió un archivo
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {

        $archivo = $_FILES['imagen_producto'];
        $tipo = $archivo['type'];
        $tamano = $archivo['size'];
        $temporal = $archivo['tmp_name'];

        // Validar el archivo (solo imágenes y tamaño máximo)
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_tamano = 2 * 1024 * 1024; // 2 MB

        if (!in_array($tipo, $tipos_permitidos)) {
            $mensaje_producto = "Error: El archivo debe ser una imagen (JPG, PNG, GIF o WebP).";
        } elseif ($tamano > $max_tamano) {
            $mensaje_producto = "Error: La imagen no puede pesar más de 2 MB.";
        } else {
            // Crear un nombre único para la imagen para evitar sobreescribir
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombre_imagen = uniqid('producto_', true) . '.' . $extension;
            $ruta_destino = 'uploads/productos/' . $nombre_imagen;

            // Mover el archivo de la carpeta temporal a la carpeta final
            if (move_uploaded_file($temporal, $ruta_destino)) {
                // La imagen se subió correctamente. $nombre_imagen ya tiene el valor correcto.
            } else {
                $mensaje_producto = "Error: Ocurrió un problema al mover la imagen.";
                $nombre_imagen = null; // Reiniciamos la variable por si acaso
            }
        }
    }
    // --- FIN DE LÓGICA DE SUBIDA DE IMAGEN ---


    // Si no hubo errores en la subida de imagen, guardamos el producto
    if (empty($mensaje_producto)) {
        if (empty($nombre_producto)) {
            $mensaje_producto = "El nombre del producto es obligatorio.";
        } else {
            // Lógica para insertar en la BD (ahora con la imagen)
            try {
                $sql = "INSERT INTO productos (nombre_producto, descripcion, precio, imagen) VALUES (:nombre, :descripcion, :precio, :imagen)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nombre', $nombre_producto);
                $stmt->bindParam(':descripcion', $descripcion_producto);
                $stmt->bindParam(':precio', $precio_producto);
                $stmt->bindParam(':imagen', $nombre_imagen); // Guardamos el nombre del archivo o NULL
                $stmt->execute();
                $mensaje_producto = "¡Producto agregado con éxito!";
            } catch (PDOException $e) {
                $mensaje_producto = "Error al guardar el producto: " . $e->getMessage();
            }
        }
    }
}

// Lógica para PROCESAR la edición de productos
if (isset($_POST['actualizar_producto'])) {
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $nombre_producto = trim($_POST['nombre_producto']);
    $descripcion_producto = trim($_POST['descripcion_producto']);
    $precio_producto = trim($_POST['precio_producto']);
    $nombre_imagen = null;

    // Verificar si se subió un archivo
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen_producto'];
        $tipo = $archivo['type'];
        $tamano = $archivo['size'];
        $temporal = $archivo['tmp_name'];

        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_tamano = 2 * 1024 * 1024; // 2 MB

        if (!in_array($tipo, $tipos_permitidos)) {
            $mensaje_lista_productos = "Error: El archivo debe ser una imagen (JPG, PNG, GIF o WebP).";
        } elseif ($tamano > $max_tamano) {
            $mensaje_lista_productos = "Error: La imagen no puede pesar más de 2 MB.";
        } else {
            // Crear un nombre único para la imagen
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombre_imagen = uniqid('producto_', true) . '.' . $extension;
            $ruta_destino = 'uploads/productos/' . $nombre_imagen;

            if (move_uploaded_file($temporal, $ruta_destino)) {
                // La imagen se subió correctamente
            } else {
                $mensaje_lista_productos = "Error: Ocurrió un problema al mover la imagen.";
                $nombre_imagen = null;
            }
        }
    }

    // Si no hubo errores en la subida de imagen, actualizamos el producto
    if (empty($mensaje_lista_productos)) {
        try {
            if ($nombre_imagen) {
                // Si hay nueva imagen, actualizamos con la imagen
                $sql = "UPDATE productos SET nombre_producto = :nombre, descripcion = :descripcion, precio = :precio, imagen = :imagen WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nombre', $nombre_producto);
                $stmt->bindParam(':descripcion', $descripcion_producto);
                $stmt->bindParam(':precio', $precio_producto);
                $stmt->bindParam(':imagen', $nombre_imagen);
                $stmt->bindParam(':id', $id_producto, PDO::PARAM_INT);
            } else {
                // Si no hay nueva imagen, actualizamos sin cambiar la imagen
                $sql = "UPDATE productos SET nombre_producto = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nombre', $nombre_producto);
                $stmt->bindParam(':descripcion', $descripcion_producto);
                $stmt->bindParam(':precio', $precio_producto);
                $stmt->bindParam(':id', $id_producto, PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                // Guardamos el mensaje en sesión y indicamos que debe mostrar la lista
                $_SESSION['mensaje_lista_productos'] = "Producto actualizado con éxito.";
                $_SESSION['mostrar_lista_productos'] = true;
                header("Location: dashboard.php");
                exit;
            } else {
                $mensaje_lista_productos = "Error al actualizar el producto.";
            }
        } catch (PDOException $e) {
            $mensaje_lista_productos = "Error en la base de datos: " . $e->getMessage();
        }
    }
}

// Obtener la lista de productos con filtro
$productos = [];
try {
    if (!empty($filtro_producto)) {
        $sql = "SELECT * FROM productos WHERE nombre_producto LIKE :filtro ORDER BY nombre_producto ASC";
        $stmt = $pdo->prepare($sql);
        $filtro_like = '%' . $filtro_producto . '%';
        $stmt->bindParam(':filtro', $filtro_like, PDO::PARAM_STR);
    } else {
        $sql = "SELECT * FROM productos ORDER BY nombre_producto ASC";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje_lista_productos = "Error al obtener los productos: " . $e->getMessage();
}

// --- FIN DE LA LÓGICA ---
?>
<!-- importacion del head -->
<?php
include "head.php";
?>

<body>
    <!-- importa el nav -->
    <?php
    include "nav.php";
    ?>

    <form method="post">
        <div class="botonesAccion">
            <button type="submit" name="accion" value="nuevoProducto">AGREGAR PRODUCTO</button>
            <button type="submit" name="accion" value="cambiarContrasenia">CAMBIAR CONTRASEÑA</button>
            <button type="submit" name="accion" value="verProductos">VER/EDITAR PRODUCTOS</button>
        </div>
    </form>

    <?php
    //  Lógica para INCLUIR el formulario si la variable $mostrar_form_password es true
    if ($mostrar_form_password) {
        // Pasamos el mensaje (ya sea de éxito o de error) a la variable que el formulario espera
        $mensaje = $mensaje_password;
        include "change_password.php";
    }
    if ($mostrar_form_producto) {
        $mensaje = $mensaje_producto;
        include "add_product.php";
    }
    if ($mostrar_lista_productos) {
    ?>
        <div class="lista-productos">
            <h2>Lista de Productos</h2>

            <!-- Filtro de productos -->
            <div class="filtro-productos">
                <form method="post" action="">
                    <input type="text" name="filtro" placeholder="Filtrar por nombre de producto..."
                        value="<?php echo htmlspecialchars($filtro_producto); ?>">
                    <button type="submit" name="accion" value="filtrar_productos">Filtrar</button>
                </form>
            </div>

            <?php
            // Mostrar mensaje de la URL solo si estamos en la lista de productos
            if ($mostrar_lista_productos && !empty($mensaje_url)) {
                if ($mensaje_url === 'actualizado') {
                    $mensaje_lista_productos = "Producto actualizado con éxito.";
                }
            ?>

            <?php
            }
            ?>
            <!-- Mensaje de éxito o error -->
            <?php if (!empty($mensaje_lista_productos)): ?>
                <div class="mensaje <?php echo strpos($mensaje_lista_productos, 'éxito') !== false ? 'exito' : 'error'; ?>">
                    <?php echo $mensaje_lista_productos; ?>
                </div>
            <?php endif; ?>

            <!-- Tabla de productos -->
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="5">No se encontraron productos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                <td>$<?php echo htmlspecialchars($producto['precio']); ?></td>
                                <td>
                                    <?php if ($producto['imagen']): ?>
                                        <img src="uploads/productos/<?php echo htmlspecialchars($producto['imagen']); ?>"
                                            alt="Producto" width="50">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- FORMULARIO SEPARADO PARA EDITAR -->
                                    <form method="post" style="display: inline; margin-right: 10px;">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                                        <input type="hidden" name="accion" value="editarProducto">
                                        <button type="submit" style="background: #03a008ff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Editar</button>
                                    </form>

                                    <!-- FORMULARIO SEPARADO PARA ELIMINAR -->
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                                        <input type="hidden" name="accion" value="eliminarProducto">
                                        <button type="submit" style="background: #b61206ff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    //  Lógica para INCLUIR el formulario de edición si la variable $mostrar_form_edicion es true
    if ($mostrar_form_edicion && $producto_a_editar) {
    ?>


        <div class="contenido">

            <?php if (!empty($mensaje_lista_productos)): ?>
                <div class="mensaje <?php echo strpos($mensaje_lista_productos, 'éxito') !== false ? 'exito' : 'error'; ?>">
                    <?php echo $mensaje_lista_productos; ?>
                </div>
            <?php endif; ?>


            <form method="post" enctype="multipart/form-data">

                <div class="add_productos">

                    <h2>Editar Producto</h2>
                    <input type="hidden" name="id_producto" value="<?php echo $producto_a_editar['id']; ?>">
                    <input type="hidden" name="accion" value="actualizar_producto">

                    <div class="form-group">
                        <label for="nombre_producto">Nombre del Producto:</label>
                        <input type="text" id="nombre_producto" name="nombre_producto"
                            value="<?php echo htmlspecialchars($producto_a_editar['nombre_producto']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_producto">Descripción:</label>
                        <textarea id="descripcion_producto" name="descripcion_producto" rows="4" required>
                        <?php echo htmlspecialchars($producto_a_editar['descripcion']); ?>
                    </textarea>
                    </div>

                    <div class="form-group">
                        <label for="precio_producto">Precio:</label>
                        <input type="number" id="precio_producto" name="precio_producto"
                            value="<?php echo htmlspecialchars($producto_a_editar['precio']); ?>" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="imagen_producto">Imagen (dejar en blanco si no quieres cambiarla):</label>
                        <?php if ($producto_a_editar['imagen']): ?>
                            <img src="uploads/productos/<?php echo htmlspecialchars($producto_a_editar['imagen']); ?>"
                                alt="Producto actual" width="100">
                        <?php endif; ?>
                        <input type="file" id="imagen_producto" name="imagen_producto" accept="image/*">
                    </div>

                    <div class="form-buttons">
                        <button type="submit" name="actualizar_producto">Actualizar Producto</button>
                        <button type="button" onclick="window.location.href='dashboard.php'">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>

    <?php
    }
    ?>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');

            if (hamburger && navLinks) {
                hamburger.addEventListener('click', () => {
                    hamburger.classList.toggle('active');
                    navLinks.classList.toggle('show');
                });

                // Cerrar menú cuando se hace clic en un enlace
                document.querySelectorAll('.nav-links a').forEach(link => {
                    link.addEventListener('click', () => {
                        hamburger.classList.remove('active');
                        navLinks.classList.remove('show');
                    });
                });

                // Cerrar menú cuando se hace clic fuera
                document.addEventListener('click', (e) => {
                    if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
                        hamburger.classList.remove('active');
                        navLinks.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>