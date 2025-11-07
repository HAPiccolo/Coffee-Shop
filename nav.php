<?php session_start(); ?>
<nav>

    <div class="menu">
        <a href="index.php"><img src="./img/coffeeshop.png" alt="Logo empresa" srcset=""></a>
        <li>
            <ul><a href="./index.php">INICIO</a></ul>
            <ul><a href="./index.php#productos">PRODUCTOS</a></ul>
            <ul><a href="./index.php#nosotros_banner">NOSOTROS</a></ul>
            <?php if ($_SESSION['usuario_rol'] == 1): ?>
                <ul><a href="./dashboard.php">DASHBOARD</a></ul>
                <ul><a href="./logout.php">SALIR</a></ul>
            <?php else: ?>
                <ul><a href="./login.php">INGRESAR</a></ul>
            <?php endif; ?>


        </li>
    </div>
</nav>