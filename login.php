<?php
include "./database.php";
include "./head.php";
?>
<style>
    body {
        background-image: url("./img/background1.jpeg");
        background-size: cover;
    }
</style>
<div class="section">
    <div class="login">
        <div class="card_login">
            <h3>INICIAR SESION</h3>
            <form action="" method="post">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="admin">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="1234">
                <div class="btnLogin">
                    <a href="./index.php">Cancelar</a>
                    <button type="submit" id="btnIngresar">Aceptar</button>

                </div>
            </form>
            <div class="mensaje">
                <?php
                session_start();

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nombre = trim($_POST['usuario']);
                    $password = trim($_POST['password']);

                    $sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['nombre' => $nombre]);
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($usuario && password_verify($password, $usuario['password'])) {
                        // --- Guardar datos en sesión ---
                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['usuario_nombre'] = $usuario['nombre'];
                        $_SESSION['usuario_rol'] = $usuario['rol'];

                        // --- Redirigir según el rol ---
                        if ($usuario['rol'] == 1) {
                            header("Location: ./dashboard.php"); // panel del admin
                        } //else {
                        //  header("Location: u.php"); // panel del usuario normal
                        //}
                        //exit;
                    } else {
                        echo "<p>❌ Usuario o contraseña incorrectos.</p>";
                    }
                }
                ?>

            </div>
        </div>

    </div>
</div>