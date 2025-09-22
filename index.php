<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Document</title>
</head>

<body>

    <?php
    include "banner.php";
    ?>


    <section class="info">
        <img src="./img/background1.jpeg" alt="" srcset="">
        <p>
            LOS SABORES DEL MUNDO AL ALCANCE DE TU MANOS
        </p>
    </section>




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