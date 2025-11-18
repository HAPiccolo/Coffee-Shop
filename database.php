<?php
// Archivo: database.php
$host = 'localhost';
$dbname = 'cafetera';
$username = 'root';
$password = 'noxon';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear tabla si no existe
    $sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol INT(1) DEFAULT 0 NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    // Ejecuta la creacion de la primer tabla
    $pdo->exec($sql);

    $sql2 = "
CREATE TABLE IF NOT EXISTS productos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255),
    precio VARCHAR(255),
    imagen VARCHAR(1000)
)";

    // Ejecuta la creacion de la segunda tabla
    $pdo->exec($sql2);

    // --- Crear usuario admin por defecto si no existe ---
    $adminUser = 'admin';
    $adminPass = password_hash('cafeteros', PASSWORD_BCRYPT); // contraseña encriptada
    $adminRol = 1;
    $check = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = :nombre");
    $check->execute(['nombre' => $adminUser]);
    $exists = $check->fetchColumn();

    if ($exists == 0) {
        $insert = $pdo->prepare("INSERT INTO usuarios (nombre, password, rol) VALUES (:nombre, :password, :rol)");
        $insert->execute(['nombre' => $adminUser, 'password' => $adminPass, 'rol' => $adminRol]);
    }
} catch (PDOException $e) { // Si ocurrio un error lo muestra
    die("Error de conexión: " . $e->getMessage());
}
