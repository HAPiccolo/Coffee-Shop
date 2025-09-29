<?php
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
        </div>

    </div>
</div>