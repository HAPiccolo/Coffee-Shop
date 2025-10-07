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

            <img src="./img/background1.jpeg" alt="" srcset="">

            <h2>Titurlo producto</h2>
            <p>Descripcion</p>
            <!-- <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/background1.jpeg" alt="" srcset="">
            <h2>Titurlo producto</h2>
            <p>Descripcion</p>
            <!-- <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/coffeeshop.png" alt="" srcset="">
            <h2>Titurlo producto</h2>
            <p>Descripcion</p>
            <!--  <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/background1.jpeg" alt="" srcset="">
            <h2>Titurlo producto</h2>
            <p>Descripcion</p>
            <!--  <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">
            <img src="./img/background1.jpeg" alt="" srcset="">
            <h2>Titurlo producto</h2>
            <p>Descripcion</p>
            <!-- <div class="precio">$249.99</div>
             <button class="btn-comprar">Comprar ahora</button> -->

        </div>
        <div class="prod_card">

            <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Altavoz Bluetooth">

            <h2>Altavoz Bluetooth</h2>
            <p>Lleva tu música a todas partes con este potente altavoz Bluetooth. Sonido cristalino y diseño resistente al agua.</p>
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

    <!-- importacion del footer -->
    <?php
    include "footer.php";
    ?>
</body>

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