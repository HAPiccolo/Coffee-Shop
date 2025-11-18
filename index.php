<!-- importacion del head -->
<?php
// CAMBIO 1: Incluir la conexión a la base de datos
include "database.php";

include "head.php";
?>

<body>
    <!-- importa el nav -->
    <?php
    include "nav.php";
    ?>


    <section class="info">
        <div class="img_info">
            <img src="./img/cafe02.jpg" alt="" srcset="">
        </div>
    </section>
    <section class="about">
        <div class="card">

            <p>En Coffee Shop creemos que una buena taza de café puede transformar cualquier momento del día. Por eso nos dedicamos a ofrecer café de la más alta calidad, cuidadosamente seleccionado y presentado en diferentes formatos para adaptarse a tu gusto y estilo de vida.</p>

            <p>Nuestro compromiso es brindarte una experiencia única, desde el aroma que despierta tus sentidos hasta el sabor que acompaña tus mejores momentos. Ya sea que prefieras café en grano, molido o en cápsulas, tenemos la presentación ideal para vos.</p>

            <p>Más que una tienda, somos amantes del café. Queremos que cada sorbo sea especial y que disfrutes de la calidez y la energía que solo un buen café puede darte.</p>

            <h2><span style="color: #734313;">COFFEE</span> <span style="color: #aa8c36;">SHOP</span></h2>
        </div>
        <div class="img_about">
            <img src="./img/mozo_cafe.jpg" alt="mozo sirviendo cafe">

        </div>
    </section>
    <section>
        <div class="productos" id="productos">
            <span class="titulo"><span style="color: #734313;">NUESTROS</span> <span style="color: #f2f2f2f2;">PRODUCTOS</span></span>
        </div>
    </section>

    <!-- CAMBIO 2: Reemplazar toda esta sección con código PHP -->
    <section class="tarjetas_productos">
        <?php
        // 1. Preparamos y ejecutamos la consulta para obtener todos los productos
        $sql = "SELECT nombre_producto, descripcion, precio, imagen FROM productos";
        $stmt = $pdo->query($sql);

        // 2. Recorremos cada producto que encontramos en la base de datos
        while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // 3. Definimos la ruta de la imagen. Si no hay imagen, usamos una por defecto.
            //    (Crea una imagen llamada 'placeholder.jpg' en tu carpeta /img)
            $ruta_imagen = !empty($producto['imagen']) ? 'uploads/productos/' . htmlspecialchars($producto['imagen']) : './img/placeholder.jpg';
        ?>
            <div class="prod_card">
                <div class="prod_img">
                    <a href="<?php echo $ruta_imagen; ?>">
                        <img src="<?php echo $ruta_imagen; ?>" alt="imagen del producto <?php echo htmlspecialchars($producto['nombre_producto']); ?>" srcset="">
                    </a>
                </div>
                <h2><?php echo htmlspecialchars($producto['nombre_producto']); ?></h2>
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>

                <!-- CAMBIO 3: Descomenté y dinamicicé el precio y el botón -->
                <!--v class="precio">$<?php echo htmlspecialchars($producto['precio']); ?></div> -->
                <!-- <button class="btn-comprar">Comprar ahora</button> -->

            </div>
        <?php
        } // Cerramos el while
        ?>
    </section>
    <!-- FIN DEL CAMBIO 2 -->

    <section id="nosotros_banner">
        <span class="titulo"><span style="color: #fff;">SOBRE </span><span style="color: #aa8c36;">NOSOTROS</span> </span>


    </section>
    <section id="nosotros">
        <div class="about">
            <div class="card">
                <h3><span style="color: #734313;">QUIENES</span> <span style="color: #aa8c36;">SOMOS</span></h3>
                <br>
                En Coffee Shop somos un equipo apasionado por el café y la calidad. Nacimos con la idea de acercar los mejores granos del mundo
                a las manos de quienes disfrutan cada detalle de una buena taza. Trabajamos con tostadores locales y productores comprometidos con
                prácticas sustentables para ofrecer un producto fresco, auténtico y con identidad.
            </div>

            <div class="card">
                <h3><span style="color: #734313;">NUESTRA</span> <span style="color: #aa8c36;">FILOSOFIA</span></h3>
                <br>
                Creemos que el café no es solo una bebida, sino una experiencia que conecta a las personas.
                Por eso cuidamos cada paso del proceso: desde la selección del grano hasta el momento en que llega a tu taza.
                Valoramos la transparencia, la sustentabilidad y el respeto por quienes hacen posible cada cosecha.
            </div>
        </div>
    </section>

    <section id="ubicacion">
        <h1>ENCONTRANOS</h1>
        <p>Visitanos en nuestra cafetería ubicada en "La Rioja y Pellegrini", te esperamos con el mejor café y un ambiente cálido.</p>
        <br>
        <div style="border:0; width:100%; height:400px; ">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1353.8461855310036!2d-58.83727683620389!3d-27.4654047690316!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sar!4v1759853049170!5m2!1ses-419!2sar" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <!-- importacion del footer -->
    <?php
    include "footer.php";
    ?>

    <!-- Boton al inicio -->
    <button id="btnTop" title="Volver arriba">⬆</button>
</body>

<!-- Script del boton que permite subir al inicio de la pagina -->

<script>
    // Referencia al botón
    const btnTop = document.getElementById("btnTop");

    // Mostrar el botón cuando se hace scroll
    window.onscroll = function() {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            btnTop.style.display = "block";
        } else {
            btnTop.style.display = "none";
        }
    };

    // Al hacer clic, sube suavemente al inicio
    btnTop.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
</script>

<!-- script que hace glass al nav -->
<script>
    window.addEventListener("scroll", function() {
        const nav = document.querySelector("nav");
        if (window.scrollY > 50) {
            nav.classList.add("scrolled");
        } else {
            nav.classList.remove("scrolled");
        }

    });
</script>

</html>