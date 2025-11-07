<?php
session_start();


// Si querés restringir solo a administradores
function verificarAdmin()
{
    if ($_SESSION['usuario_rol'] != 1) {
        header("Location: no_autorizado.php");
        exit;
    }
}
