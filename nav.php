<?php session_start(); ?>
<nav>
    <div class="menu">
        <a href="index.php">
            <img src="./img/coffeeshop.png" alt="Logo empresa" srcset="">
        </a>
        <!-- Botón hamburguesa para móviles -->
        <button class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
        <ul class="nav-links">
            <li><a href="./index.php">INICIO</a></li>
            <li><a href="./index.php#productos">PRODUCTOS</a></li>
            <li><a href="./index.php#nosotros_banner">NOSOTROS</a></li>
            <?php if ($_SESSION['usuario_rol'] == 1): ?>
                <li><a href="./dashboard.php">DASHBOARD</a></li>
                <li><a href="./logout.php">SALIR</a></li>
            <?php else: ?>
                <li><a href="./login.php">INGRESAR</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>