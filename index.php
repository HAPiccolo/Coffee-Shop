<!-- importacion del head -->
<?php
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
    <section class="tarjetas_productos">
        <div class="prod_card">

            <img src="./img/productos/prod1.webp" alt="imagen del producto" srcset="">

            <h2>Capsulas Starbucks</h2>
            <p>18 capsulas con el sabor mas intenso de STARBUCKS</p>
            <!-- <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/productos/prod2.webp" alt="imagen del producto" srcset="">
            <h2>Drip Coffee Brasil Minas Gerais</h2>
            <p>Este pack contiene 5 sobres de 10g con Café de Especialidad Brasil Intenso</p>
            <!-- <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/productos/prod3.webp" alt="imagen del producto" srcset="">
            <h2>Café de Especialidad Colombiana Bourbon</h2>
            <p>Café con notas de frutos tropicales, miel de caña y breva.</p>
            <!--  <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/productos/prod4.webp" alt="imagen del producto" srcset="">
            <h2>Café Molido Brasil 250g</h2>
            <p>Café tipo Arabica de cuerpo medio Acidulado leve</p>
            <!--  <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/productos/prod5.webp" alt="" srcset="">
            <h2>Café Molido Tipo Italiano 250g</h2>
            <p>Tostado natural de acidez media y aroma muy pronunciado</p>
            <!-- <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">

            <img src="./img/productos/prod6.webp" alt="imagen del producto">

            <h2>Combo 80 capsulas</h2>
            <p>80 capsulas compatibles con maquinas NESPRESSO</p>
            <!-- <div class="precio">$129.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->
        </div>
    </section>

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

    // Mostrar el botón cuando el usuario hace scroll
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