<?php
include "auth.php";
verificarAdmin(); // solo admin accede

// Incluimos la conexión a la BD para poder usarla en la lógica de abajo
include "database.php";

// --- INICIO DE LA LÓGICA ---

// Variable para controlar si mostramos el formulario de contraseña
$mostrar_form_password = false;
$mostrar_form_producto = false;

// Variable para guardar el mensaje de éxito o error
$mensaje_password = '';
// Inicializar la variable de mensaje del producto
$mensaje_producto = '';

// Lógica para MOSTRAR el formulario cuando se presiona el botón
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cambiarContrasenia') {
    $mostrar_form_password = true;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'nuevoProducto') {
    // <-- CORRECCIÓN 1: Usar la variable correcta
    $mostrar_form_producto = true;
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

    // 1. Verificar si se subió un archivo
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {

        $archivo = $_FILES['imagen_producto'];
        $tipo = $archivo['type'];
        $tamano = $archivo['size'];
        $temporal = $archivo['tmp_name'];

        // 2. Validar el archivo (solo imágenes y tamaño máximo)
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_tamano = 2 * 1024 * 1024; // 2 MB

        if (!in_array($tipo, $tipos_permitidos)) {
            $mensaje_producto = "Error: El archivo debe ser una imagen (JPG, PNG, GIF o WebP).";
        } elseif ($tamano > $max_tamano) {
            $mensaje_producto = "Error: La imagen no puede pesar más de 2 MB.";
        } else {
            // 3. Crear un nombre único para la imagen para evitar sobreescribir
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombre_imagen = uniqid('producto_', true) . '.' . $extension;
            $ruta_destino = 'uploads/productos/' . $nombre_imagen;

            // 4. Mover el archivo de la carpeta temporal a la carpeta final
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
    ?>



</body>

</html>